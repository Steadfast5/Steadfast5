<?php

namespace pocketmine\level\generator\normal\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\CactusStack;
use pocketmine\level\generator\populator\VariableAmountPopulator;
use pocketmine\utils\Random;

class Cactus extends VariableAmountPopulator {

	/** @var ChunkManager */
	private $level;

	public function __construct() {
		parent::__construct(2, 1);
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		$cactus = new CactusStack($random);
		for ($i = 0; $i < $amount; ++$i) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			$cactus->randomize();
			if ($y !== -1 && $cactus->canPlaceObject($level, $x, $y, $z)) {
				$cactus->placeObject($level, $x, $y, $z);
			}
		}
	}

	private function getHighestWorkableBlock($x, $z) {
		for ($y = 127; $y >= 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR && $b !== Block::LEAVES && $b !== Block::LEAVES2) {
				break;
			}
		}
		return $y === 0 ? -1 : ++$y;
	}

}
