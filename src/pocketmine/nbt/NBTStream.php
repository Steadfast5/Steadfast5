<?php

namespace pocketmine\nbt;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntArray;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Binary;
use pocketmine\utils\BinaryDataException;
use function call_user_func;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;
use function strlen;
use function substr;
use function zlib_decode;
use function zlib_encode;

abstract class NBTStream {

	public $buffer = "";
	public $offset = 0;

	public function get($len) {
		if ($len === 0) {
			return "";
		}

		$buflen = strlen($this->buffer);
		if ($len === true) {
			$str = substr($this->buffer, $this->offset);
			$this->offset = $buflen;
			return $str;
		}
		if ($len < 0) {
			$this->offset = $buflen - 1;
			return "";
		}
		$remaining = $buflen - $this->offset;
		if ($remaining < $len) {
			throw new BinaryDataException("Not enough bytes left in buffer: need $len, have $remaining");
		}
		return $len === 1 ? $this->buffer[$this->offset++] : substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	public function put(string $v) {
		$this->buffer .= $v;
	}

	public function feof() {
		return !isset($this->buffer[$this->offset]);
	}

	public function read(string $buffer, bool $doMultiple = false, int &$offset = 0, int $maxDepth = 0) {
		$this->offset = &$offset;
		$this->buffer = $buffer;
		$data = $this->readTag(new ReaderTracker($maxDepth));

		if ($data === null) {
			throw new \InvalidArgumentException("Found TAG_End at the start of buffer");
		}

		if ($doMultiple && !$this->feof()) {
			$data = [$data];
			do {
				$tag = $this->readTag(new ReaderTracker($maxDepth));
				if ($tag !== null) {
					$data[] = $tag;
				}
			} while(!$this->feof());
		}
		$this->buffer = "";
		return $data;
	}

	public function readCompressed(string $buffer) {
		return $this->read(zlib_decode($buffer));
	}

	public function write($data) {
		$this->offset = 0;
		$this->buffer = "";

		if ($data instanceof NamedTag) {
			$this->writeTag($data);
			return $this->buffer;
		} elseif (is_array($data)) {
			foreach ($data as $tag) {
				$this->writeTag($tag);
			}
			return $this->buffer;
		}
		return false;
	}

	public function writeCompressed($data, int $compression = ZLIB_ENCODING_GZIP, int $level = 7) {
		if (($write = $this->write($data)) !== false) {
			return zlib_encode($write, $compression, $level);
		}
		return false;
	}

	public function readTag(ReaderTracker $tracker) {
		$tagType = $this->getByte();
		if ($tagType === NBT::TAG_End) {
			return null;
		}

		$tag = NBT::createTag($tagType);
		$tag->setName($this->getString());
		$tag->read($this, $tracker);

		return $tag;
	}

	public function writeTag(NamedTag $tag) {
		$this->putByte($tag->getType());
		$this->putString($tag->getName());
		$tag->write($this);
	}

	public function writeEnd() {
		$this->putByte(NBT::TAG_End);
	}

	public function getByte() {
		return Binary::readByte($this->get(1));
	}

	public function getSignedByte() {
		return Binary::readSignedByte($this->get(1));
	}

	public function putByte(int $v) {
		$this->buffer .= Binary::writeByte($v);
	}

	abstract public function getShort();

	abstract public function getSignedShort();

	abstract public function putShort(int $v);


	abstract public function getInt();

	abstract public function putInt(int $v);

	abstract public function getLong() ;

	abstract public function putLong(int $v);


	abstract public function getFloat();

	abstract public function putFloat(float $v);


	abstract public function getDouble();

	abstract public function putDouble(float $v);

	public function getString() {
		return $this->get(self::checkReadStringLength($this->getShort()));
	}

	public function putString(string $v) {
		$this->putShort(self::checkWriteStringLength(strlen($v)));
		$this->put($v);
	}

	protected static function checkReadStringLength(int $len) {
		if ($len > 32767) {
			throw new \UnexpectedValueException("NBT string length too large ($len > 32767)");
		}
		return $len;
	}

	protected static function checkWriteStringLength(int $len) {
		if ($len > 32767) {
			throw new \InvalidArgumentException("NBT string length too large ($len > 32767)");
		}
		return $len;
	}

	abstract public function getIntArray();

	abstract public function putIntArray(array $array);

	public static function toArray(CompoundTag $data){
		$array = [];
		self::tagToArray($array, $data);
		return $array;
	}

	private static function tagToArray(array &$data, NamedTag $tag) {
		foreach ($tag as $key => $value) {
			if ($value instanceof CompoundTag || $value instanceof ListTag || $value instanceof IntArrayTag) {
				$data[$key] = [];
				self::tagToArray($data[$key], $value);
			} else {
				$data[$key] = $value->getValue();
			}
		}
	}

	public static function fromArrayGuesser(string $key, $value) {
		if (is_int($value)) {
			return new IntTag($key, $value);
		} elseif (is_float($value)) {
			return new FloatTag($key, $value);
		} elseif (is_string($value)) {
			return new StringTag($key, $value);
		} elseif (is_bool($value)) {
			return new ByteTag($key, $value ? 1 : 0);
		}

		return null;
	}

	private static function tagFromArray(NamedTag $tag, array $data, callable $guesser) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$isNumeric = true;
				$isIntArray = true;
				foreach ($value as $k => $v) {
					if (!is_numeric($k)) {
						$isNumeric = false;
						break;
					} elseif (!is_int($v)) {
						$isIntArray = false;
					}
				}
				$tag[$key] = $isNumeric ? ($isIntArray ? new IntArrayTag($key, []) : new ListTag($key, [])) : new CompoundTag($key, []);
				self::tagFromArray($tag->{$key}, $value, $guesser);
			} else {
				$v = call_user_func($guesser, $key, $value);
				if ($v instanceof NamedTag) {
					$tag[$key] = $v;
				}
			}
		}
	}

	public static function fromArray(array $data, callable $guesser = null) {
		$tag = new CompoundTag("", []);
		self::tagFromArray($tag, $data, $guesser ?? [self::class, "fromArrayGuesser"]);
		return $tag;
	}

}
