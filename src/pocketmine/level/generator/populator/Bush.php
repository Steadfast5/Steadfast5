<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\structure\Bush;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class Bush extends Populator {

	protected $level;
	protected $type;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		for ($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y === -1) {
				continue;
			}
			$tree = new Tree::$types[$this->type]();
			$bush = new Bush($tree->leafBlock, $tree->leafType ?? $tree->type);
			$bush->placeObject($level, $x, $y, $z, $random);
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
