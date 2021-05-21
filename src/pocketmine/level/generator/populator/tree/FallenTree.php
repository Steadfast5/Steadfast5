<?php

namespace pocketmine\level\generator\populator\tree;

use pocketmine\block\Block;
use pocketmine\block\Wood;
use pocketmine\level\generator\populator\Populator;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class FallenTree extends Populator {

	private $type = Wood::OAK;

	public function __construct(int $type = Wood::OAK) {
		$this->type = $type;
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
//		if($random->nextRange(0, 20) > 5) {
//			return;
//		}
		$x = $chunkX << 4;
		$z = $chunkZ << 4;
		$y = $this->getHighestWorkableBlock($level, $x, $z);
		if ($y == -1) {
			return;
		}
		$v = new Vector3($x, $y, $z);
		$side = ($r = $random->nextRange(0, 3)) == 0 ? Vector3::SIDE_EAST : ($r == 1 ? Vector3::SIDE_NORTH : ($r == 2 ? Vector3::SIDE_SOUTH : Vector3::SIDE_WEST));

		$multiplier = $side == Vector3::SIDE_EAST || $side == Vector3::SIDE_WEST ?: 2;
		$lenght = $random->nextRange(3, 5);
		for ($i = 0; $i < $lenght; $i++) {
			$vector = $v->getSide($side, $i);
			$level->setBlockIdAt($vector->getX(), $y, $vector->getZ(), Block::WOOD);
			$level->setBlockDataAt($vector->getX(), $y, $vector->getZ(), $this->getWoodType() + 4 * $multiplier);
			if ($random->nextRange(0, 63) < 16) {
				$level->setBlockIdAt($vector->getX(), $y + 1, $vector->getZ(), $random->nextBoolean() ? Block::RED_MUSHROOM : Block::BROWN_MUSHROOM);
			}
		}
	}

	public function getHighestWorkableBlock(ChunkManager $level, int $x, int $z) {
		for ($y = 127; $y > 0; $y--) {
			$b = $level->getBlockIdAt($x, $y, $z);
			if ($b == Block::GRASS) {
				return $y + 1;
			}
		}
		return -1;
	}

	public function getWoodType() {
		return $this->type;
	}

}
