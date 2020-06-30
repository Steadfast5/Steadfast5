<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\structure\Dungeons;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Dungeon extends Populator {

	protected $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		if ($amount == 5) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $random->nextRange(10, $this->getHighestWorkableBlock($x, $z) - 6);
			$d = new Dungeons();
			$d->placeObject($level, $x, $y, $z, $random);
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT || $b === Block::GRASS || $b === Block::PODZOL || $b === Block::SAND || $b === Block::SNOW_BLOCK || $b === Block::SANDSTONE) {
				break;
			} elseif ($b !== 0 && $b !== Block::SNOW_LAYER && $b !== Block::WATER) {
				return - 1;
			}
		}
		return ++$y;
	}

}
