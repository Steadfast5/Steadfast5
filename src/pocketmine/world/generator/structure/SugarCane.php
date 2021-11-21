<?php

namespace pocketmine\world\generator\structure;

use pocketmine\block\Block;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class SugarCane extends PopulatorObject {
	
	protected $totalHeight;

	public function canPlaceObject(ChunkManager $world, int $x, int $y, int $z, Random $random) {
		$this->totalHeight = 1 + $random->nextBoundedInt(3);
		$below = $world->getBlockIdAt($x, $y - 1, $z);
		if (
			($below == Block::SAND || $below == Block::GRASS) && (
				$world->getBlockIdAt($x + 1, $y - 1, $z) == Block::WATER ||
				$world->getBlockIdAt($x - 1, $y - 1, $z) == Block::WATER ||
				$world->getBlockIdAt($x, $y - 1, $z + 1) == Block::WATER ||
				$world->getBlockIdAt($x, $y - 1, $z - 1) == Block::WATER
			)
		) {
			return true;
		}
		return false;
	}

	public function placeObject(ChunkManager $world, int $x, int $y, int $z) {
		for($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($world->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$world->setBlockIdAt($x, $y + $yy, $z, Block::SUGARCANE_BLOCK);
		}
	}

}
