<?php

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class SugarCaneStack extends Object {

	private $random;
	private $baseHeight = 2;
	private $randomHeight = 4;
	private $totalHeight;

	public function __construct(Random $random) {
		$this->random = $random;
		$this->randomize();
	}

	public function randomize() {
		$this->totalHeight = $this->baseHeight + $this->random->nextBoundedInt($this->randomHeight);
	}

	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z) {
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		if ($level->getBlockIdAt($x, $y, $z) == Block::AIR && (
				$below == Block::DIRT ||
				$below == Block::GRASS ||
				$below == Block::SAND
			) && (
				$this->isWater($level->getBlockIdAt($x - 1, $y - 1, $z)) ||
				$this->isWater($level->getBlockIdAt($x + 1, $y - 1, $z)) ||
				$this->isWater($level->getBlockIdAt($x, $y - 1, $z - 1)) ||
				$this->isWater($level->getBlockIdAt($x, $y - 1, $z + 1))
			)
		) {
			return true;
		}
		return false;
	}

	private function isWater(int $id) {
		if ($id == Block::WATER || $id == Block::STILL_WATER) {
			return true;
		}
		return false;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for ($yy = 0; $yy < $this->totalHeight; $yy++) {
			if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::SUGARCANE_BLOCK);
		}
	}

}
