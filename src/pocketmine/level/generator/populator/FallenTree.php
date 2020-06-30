<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\structure\FallenTree;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class FallenTree extends Populator {

	protected $level;
	protected $type;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		$tree =  TreePopulator::$types[$this->type];
		$fallenTree = new FallenTree(
			new $tree()
		);
		for ($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if (isset(FallenTree::$overridable[$level->getBlockIdAt($x, $y, $z)])) {
				$y--;
			}
			if ($y !== -1 && $fallenTree->canPlaceObject($level, $x, $y + 1, $z, $random)) {
				$fallenTree->placeObject($level, $x, $y + 1, $z);
			}
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT || $b === Block::GRASS) {
				break;
			} elseif ($b !== Block::AIR && $b !== Block::SNOW_LAYER) {
				return -1;
			}
		}
		return ++$y;
	}

}
