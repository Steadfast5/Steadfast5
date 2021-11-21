<?php

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\level\generator\utils\BuildingUtils;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class LavaLake extends VariableAmountPopulator {

	protected $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$ory = $random->nextRange(20, 60);
		$y = $ory;
		for ($i = 0; $i < 4; $i++) {
			$x += $random->nextRange(-1, 1);
			$y += $random->nextRange(-1, 1);
			$z += $random->nextRange(-1, 1);
			if ($level->getBlockIdAt($x, $y, $z) !== Block::AIR) {
				BuildingUtils::buildRandom($this->level, new Vector3($x, $y, $z), new Vector3(5, 5, 5), $random, Block::get(Block::LAVA));
			}
		}
		for ($xx = $x - 8; $xx <= $x + 8; $xx++) {
			for ($zz = $z - 8; $zz <= $z + 8; $zz++) {
				for ($yy = $ory + 1; $yy <= $y + 3; $yy++) {
					if ($level->getBlockIdAt($xx, $yy, $zz) == Block::LAVA) {
						$level->setBlockIdAt($xx, $yy, $zz, Block::AIR);
					}
				}
			}
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT || $b === Block::GRASS || $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 && $b !== Block::SNOW_LAYER) {
				return -1;
			}
		}
		return ++$y;
	}

}
