<?php

namespace pocketmine\level\generator\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Bush extends PopulatorObject {

	public $overridable = [
		Block::AIR => true,
		17 => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
	];

	protected $leaf;
	protected $height;

	public function __construct($leafId = Block::LEAVES, $leafData = 0) {
		$this->leaf = [
			$leafId,
			$leafData,
		];
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$number = $random->nextBoundedInt(6);
		$pos = new Vector3($x, $y, $z);
		$this->placeLeaf($pos->x, $pos->y, $pos->z, $level);
		for ($i = 0; $i < $number; $i ++) {
			$transfer = $random->nextBoolean ();
			$direction = $random->nextBoundedInt(6);
			$newPos = $pos->getSide($direction);
			if ($transfer) {
				$pos = $newPos;
			}
			$this->placeLeaf($newPos->x, $newPos->y, $newPos->z, $level);
		}
	}

	public function placeLeaf($x, $y, $z, ChunkManager $level) {
		if (isset($this->overridable[$level->getBlockIdAt($x, $y, $z)]) && ! isset($this->overridable[$level->getBlockIdAt($x, $y - 1, $z)])) {
			$level->setBlockIdAt($x, $y, $z, $this->leaf[0]);
			$level->setBlockDataAt($x, $y, $z, $this->leaf[1]);
		}
	}

}
