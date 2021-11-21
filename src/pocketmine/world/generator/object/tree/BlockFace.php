<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class BlockFace {

	public $index;
	public $opposite;
	public $horizontalIndex;
	public $name;
	public $axisDirection;
	public $unitVector;
	public $axis;

	public function __construct(int $index, int $opposite, int $horizontalIndex, String $name, int $axisDirection, Vector3 $unitVector) {
		$this->index = $index;
		$this->opposite = $opposite;
		$this->horizontalIndex = $horizontalIndex;
		$this->name = $name;
		$this->axisDirection = $axisDirection;
		$this->unitVector = $unitVector;
	}

	public static function horizontalRandom(Random $random) {
		$faces = self::horizontal();
		return $faces[max(0, $random->nextBoundedInt(count($faces)) - 1)];
	}

	public static function horizontal() {
		return [
			new BlockFace(2, 3, 2, "north", -1, new Vector3(0, 0, -1)),
			new BlockFace(3, 2, 0, "south", 1, new Vector3(0, 0, 1)),
			new BlockFace(4, 5, 1, "west", -1, new Vector3(-1, 0, -1)),
			new BlockFace(5, 4, 3, "east", 1, new Vector3(1, 0, 0)),
		];
	}

}
