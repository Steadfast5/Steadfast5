<?php

namespace pocketmine\level;

use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\LevelException;

class WeakPosition extends Position {

	public function __construct($x = 0, $y = 0, $z = 0, Level $level = null) {
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->levelId = ($level !== null ? $level->getId() : -1);
	}

	public static function fromObject(Vector3 $pos, Level $level = null) {
		return new WeakPosition($pos->x, $pos->y, $pos->z, $level);
	}

	public function getLevel() {
		return Server::getInstance()->getLevel($this->levelId);
	}

	public function setLevel(Level $level) {
		$this->levelId = ($level !== null ? $level->getId() : -1);
		return $this;
	}

	public function getSide($side, $step = 1) {
		assert($this->isValid());
		return WeakPosition::fromObject(parent::getSide($side, $step), $this->level);
	}

	public function __toString() {
		return "Weak" . parent::__toString();
	}

}
