<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\structure\Igloo;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class Igloo extends Populator {

	protected $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		if ($random->nextBoundedInt(100) > 30) {
			return;
		}
		$igloo = new Igloo();
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$y = $this->getHighestWorkableBlock($x, $z) - 1;
		if ($igloo->canPlaceObject($level, $x, $y, $z, $random)) {
			$igloo->placeObject($level, $x, $y, $z, $random);
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT || $b === Block::GRASS || $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 && $b !== Block::SNOW_LAYER) {
				return - 1;
			}
		}
		return ++$y;
	}

}
