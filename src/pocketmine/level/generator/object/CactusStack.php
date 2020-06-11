<?php

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class CactusStack {

	/** @var Random */
	private $random;
	private $baseHeight = 1;
	private $randomHeight = 3;
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
		if ($level->getBlockIdAt($x, $y, $z) == Block::AIR &&
			($below == Block::SAND || $below == Block::CACTUS) && (
				$level->getBlockIdAt($x - 1, $y - 1, $z) == Block::AIR &&
				$level->getBlockIdAt($x + 1, $y - 1, $z) == Block::AIR &&
				$level->getBlockIdAt($x, $y - 1, $z - 1) == Block::AIR &&
				$level->getBlockIdAt($x, $y - 1, $z + 1) == Block::AIR
			)
		) {
			return true;
		}
		return false;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for ($yy = 0; $yy < $this->totalHeight; $yy++) {
			if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
		}
	}

}
