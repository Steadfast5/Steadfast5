<?php

namespace pocketmine\level\generator\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class SugarCane extends PopulatorObject {
	
	protected $totalHeight;

	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->totalHeight = 1 + $random->nextBoundedInt(3);
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		if (
			($below == Block::SAND || $below == Block::GRASS) && (
				$level->getBlockIdAt($x + 1, $y - 1, $z) == Block::WATER ||
				$level->getBlockIdAt($x - 1, $y - 1, $z) == Block::WATER ||
				$level->getBlockIdAt($x, $y - 1, $z + 1) == Block::WATER ||
				$level->getBlockIdAt($x, $y - 1, $z - 1) == Block::WATER
			)
		) {
			return true;
		}
		return false;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::SUGARCANE_BLOCK);
		}
	}

}
