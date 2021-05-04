<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\structure\Temple;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class Temple extends Populator {

	protected $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		if ($random->nextBoundedInt(1000) > 70) {
			return;
		}
		$temple = new Temple();
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$y = $this->getHighestWorkableBlock($x, $z);
		if ($temple->canPlaceObject($level, $x, $y, $z, $random)) {
			$temple->placeObject($level, $x, $y - 1, $z, $random);
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::SAND) {
				break;
			}
		}
		return ++$y;
	}

}
