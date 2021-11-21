<?php

namespace pocketmine\world;

use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\WorldException;

class WeakPosition extends Position {

	public function __construct($x = 0, $y = 0, $z = 0, World $world = null) {
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->worldId = ($world !== null ? $world->getId() : -1);
	}

	public static function fromObject(Vector3 $pos, World $world = null) {
		return new WeakPosition($pos->x, $pos->y, $pos->z, $world);
	}

	public function getWorld() {
		return Server::getInstance()->getWorld($this->worldId);
	}

	public function setWorld(World $world) {
		$this->worldId = ($world !== null ? $world->getId() : -1);
		return $this;
	}

	public function getSide($side, $step = 1) {
		assert($this->isValid());
		return WeakPosition::fromObject(parent::getSide($side, $step), $this->world);
	}

	public function __toString() {
		return "Weak" . parent::__toString();
	}

}
