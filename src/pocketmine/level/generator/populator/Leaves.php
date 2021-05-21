<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class Leaves extends VariableAmountPopulator {

	private $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		for ($i = 0; $i < $amount; ++$i) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y !== -1 && $this->canLeavesStay($x, $y, $z)) {
				for ($xx = $x; $xx <= $x + 2; $xx++) {
					for ($zz = $z; $zz <= $z + 2; $zz++) {
						$level->setBlockIdAt($xx, $y, $z, Block::LEAVES);
						$level->setBlockDataAt($xx, $y, $zz, 3);
					}
				}
				$level->setBlockIdAt($x + 1, $y, $z + 1, Block::WOOD);
				$level->setBlockDataAt($x + 1, $y, $z + 1, 3);
			}
		}
	}

	private function getHighestWorkableBlock($x, $z) {
		for ($y = 127; $y >= 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR && $b !== Block::LEAVES && $b !== Block::LEAVES2 && $b !== Block::SNOW_LAYER) {
				break;
			}
		}
		return $y === 0 ? -1 : ++$y;
	}

	private function canLeavesStay($x, $y, $z) {
		$b = $this->level->getBlockIdAt($x, $y, $z);
		return ($b === Block::AIR || $b === Block::SNOW_LAYER) && $this->level->getBlockIdAt($x, $y - 1, $z) === Block::GRASS;
    }

}
