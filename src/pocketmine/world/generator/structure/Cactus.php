<?php

namespace pocketmine\world\generator\structure;

use pocketmine\block\Block;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class Cactus extends PopulatorObject {

	protected $totalHeight;

	public function canPlaceObject(ChunkManager $world, int $x, int $y, int $z, Random $random) {
		$this->totalHeight = 1 + $random->nextBoundedInt(3);
		$below = $world->getBlockIdAt($x, $y - 1, $z);
		for ($yy = $y; $yy <= $y + $this->totalHeight; $yy ++) {
			if ($world->getBlockIdAt($x, $yy, $z) !== Block::AIR || ($below !== Block::SAND && $below !== Block::CACTUS) || (
					$world->getBlockIdAt($x - 1, $yy, $z) !== Block::AIR ||
					$world->getBlockIdAt($x + 1, $yy, $z) !== Block::AIR ||
					$world->getBlockIdAt($x, $yy, $z - 1) !== Block::AIR ||
					$world->getBlockIdAt($x, $yy, $z + 1) !== Block::AIR
				)
			) {
				return false;
			}
		}
		return true;
	}

	public function placeObject(ChunkManager $world, int $x, int $y, int $z) {
		for ($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($world->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$world->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
		}
	}

}
