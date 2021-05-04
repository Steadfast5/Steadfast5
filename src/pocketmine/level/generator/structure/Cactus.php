<?php

namespace pocketmine\level\generator\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class Cactus extends PopulatorObject {

	protected $totalHeight;

	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->totalHeight = 1 + $random->nextBoundedInt(3);
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		for ($yy = $y; $yy <= $y + $this->totalHeight; $yy ++) {
			if (
				$level->getBlockIdAt($x, $yy, $z) !== Block::AIR ||
				($below !== Block::SAND && $below !== Block::CACTUS) || (
					$level->getBlockIdAt($x - 1, $yy, $z) !== Block::AIR ||
					$level->getBlockIdAt($x + 1, $yy, $z) !== Block::AIR ||
					$level->getBlockIdAt($x, $yy, $z - 1) !== Block::AIR ||
					$level->getBlockIdAt($x, $yy, $z + 1) !== Block::AIR
				)
			) {
				return false;
			}
		}
		return true;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for ($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
		}
	}

}
