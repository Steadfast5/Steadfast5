<?php

namespace pocketmine\tile;

use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\ShulkerInventory;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\NBT;
use pocketmine\tile\Container;
use pocketmine\tile\Nameable;
use pocketmine\tile\Spawnable;

abstract class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable {

	protected $inventory = null;

	public function __construct(FullChunk $chunk, Compound $nbt) {
		parent::__construct($chunk, $nbt);
		$this->inventory = new ShulkerInventory($this);
		if (!isset($this->namedtag->Items) || !($this->namedtag->Items instanceof Enum)) {
			//$this->namedtag->Items = new Enum("Items", []);
			//$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}
		for ($i = 0; $i < $this->getSize(); ++$i) {
			$this->inventory->setItem($i, $this->getItem($i), false);
		}
	}

	public function close() {
		if ($this->closed === false) {
			foreach ($this->inventory->getViewers() as $player) {
				$player->removeWindow($this->inventory);
			}
			parent::close();
		}
	}

	public function saveNBT() {
		parent::saveNBT();
		$this->namedtag->Items = new Enum("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for ($index = 0; $index < $this->getSize(); ++$index) {
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}

	public function getSize() {
		return 27;
	}

	protected function getSlotIndex($index) {
		foreach ($this->namedtag->Items as $i => $slot) {
			if ((int) $slot["Slot"] === (int) $index) {
				return (int) $i;
			}
		}
		return -1;
	}

	public function getItem($index) {
		$i = $this->getSlotIndex($index);
		if ($i < 0) {
			return Item::get(Item::AIR, 0, 0);
		} else {
			return NBT::getItemHelper($this->namedtag->Items[$i]);
		}
	}

	public function setItem($index, Item $item) {
		$i = $this->getSlotIndex($index);
		$d = NBT::putItemHelper($item, $index);
		if ($item->getId() === Item::AIR || $item->getCount() <= 0) {
			if ($i >= 0) {
				unset($this->namedtag->Items[$i]);
			}
		} elseif ($i < 0) {
			for ($i = 0; $i <= $this->getSize(); ++$i) {
				if (!isset($this->namedtag->Items[$i])) {
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		} else {
			$this->namedtag->Items[$i] = $d;
		}
		return true;
	}

	public function getRealInventory() {
		return $this->inventory;
	}

	public function getInventory() {
		return $this->inventory;
	}

	public function hasName() {
		return isset($this->namedtag->CustomName);
	}

	public function setName($str) {
		if ($str === "") {
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}

	public function getName() {
		return $this->hasName() ? $this->namedtag->CustomName->getValue() : parent::getName();
	}

	public function getSpawnCompound() {
		$compound = new Compound("", [
			new StringTag("id", TIle::SHULKER_BOX),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);
		if ($this->hasName()) {
			$compound->CustomName = $this->namedtag->CustomName;
		}

		return $compound;
	}

}
