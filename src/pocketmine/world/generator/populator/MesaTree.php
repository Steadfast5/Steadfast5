<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\generator\object\Tree as ObjectTree;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class MesaTree extends Populator {

	private $level;
	private $randomAmount;
	private $baseAmount;
	private $type;

	public function __construct($type = Sapling::OAK) {
		$this->type = $type;
	}

	public function setRandomAmount($amount) {
		$this->randomAmount = $amount;
	}

	public function setBaseAmount($amount) {
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
		for ($i = 0; $i < $amount; ++$i) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y === -1) {
				continue;
			}
			ObjectTree::growTree($this->level, $x, $y, $z, $random, $this->type);
			$level->setBlockIdAt($x, $y - 1, $z, Block::DIRT);
		}
	}

	private function getHighestWorkableBlock($x, $z) {
		for ($y = 127; $y > 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::STAINED_CLAY) {
				break;
			} elseif ($b !== 0 && $b !== Block::SNOW_LAYER) {
				return -1;
			}
		}
		return ++$y;
	}

}
