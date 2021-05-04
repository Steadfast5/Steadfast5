<?php

namespace pocketmine\level\generator\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class Igloo extends PopulatorObject {

	public $overridable = [
		Block::AIR => true,
		6 => true,
		17 => true,
		18 => true,
		Block::DANDELION => true,
		Block::POPPY => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
		Block::LEAVES2 => true,
	];

	protected $direction;

	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->direction = $random->nextBoundedInt(4);
		switch ($this->direction) {
			case 0:
				for ($xx = $x - 3; $xx <= $x + 4; $xx ++) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy ++) {
						for ($zz = $z - 3; $zz <= $z + 3; $zz ++) {
							if (! isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)])) {
								return false;
							}
						}
					}
				}
				break;
			case 1:
				for ($xx = $x - 4; $xx <= $x + 3; $xx ++) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy ++) {
						for ($zz = $z - 3; $zz <= $z + 3; $zz ++) {
							if (! isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)])) {
								return false;
							}
						}
					}
				}
				break;
			case 2:
				for ($xx = $x - 3; $xx <= $x + 3; $xx ++) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy ++) {
						for ($zz = $z - 3; $zz <= $z + 4; $zz ++) {
							if (! isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)])) {
								return false;
							}
						}
					}
				}
				break;
			case 3:
				for ($xx = $x - 3; $xx <= $x + 3; $xx ++) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy ++) {
						for ($zz = $z - 4; $zz <= $z + 3; $zz ++) {
							if (! isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)])) {
								return false;
							}
						}
					}
				}
				break;
		}
		return true;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		if (! isset($this->direction) && ! $this->canPlaceObject($level, $x, $y, $z, $random)) {
			return false;
		}
		switch ($this->direction) {
			case 0:
				for ($xx = $x - 3; $xx <= $x + 4; $xx ++) {
					for ($zz = $z - 3; $zz <= $z + 3; $zz ++) {
						if (! isset($this->overridable[$level->getBlockIdAt($xx, $y, $zz)])) {
							$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
						}
					}
				}
				for ($i = 0; $i < 2; $i ++) {
					$level->setBlockIdAt($x + 3 + $i, $y, $z, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3 + $i, $y + 3, $z, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3 + $i, $y + 1, $z + 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3 + $i, $y + 1, $z - 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3 + $i, $y + 2, $z + 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3 + $i, $y + 2, $z - 1, Block::SNOW_BLOCK);
				}
				for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
					$level->setBlockIdAt($x - 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3, $y + 2, $zz, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					$level->setBlockIdAt($xx, $y + 1, $z - 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z - 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 1, $z + 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z + 3, Block::SNOW_BLOCK);
				}
				$level->setBlockIdAt($x, $y + 1, $z + 3, Block::ICE);
				$level->setBlockIdAt($x, $y + 1, $z - 3, Block::ICE);
				for ($i = 1; $i <= 2; $i ++) {
					$level->setBlockIdAt($x + 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
				}
				for ($i = 0; $i < 3; $i ++) {
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 4, $zz, Block::SNOW_BLOCK);
						$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
					}
				}
				for ($xx = $x - 2; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 1, $zz, Block::CARPET);
					}
				}
				$level->setBlockIdAt($x - 1, $y + 1, $z + 2, Block::BED_BLOCK);
				$level->setBlockIdAt($x, $y + 1, $z + 2, Block::BED_BLOCK);
				$level->setBlockDataAt($x - 1, $y + 1, $z + 2, 9);
				$level->setBlockDataAt($x, $y + 1, $z + 2, 1);
				$level->setBlockIdAt($x - 1, $y + 1, $z - 2, Block::CRAFTING_TABLE);
				$level->setBlockIdAt($x, $y + 1, $z - 2, Block::REDSTONE_TORCH);
				$level->setBlockIdAt($x + 1, $y + 1, $z - 2, Block::FURNACE);
				break;
			case 1:
				for ($xx = $x - 4; $xx <= $x + 3; $xx ++) {
					for ($zz = $z - 3; $zz <= $z + 3; $zz ++) {
						if (! isset($this->overridable[$level->getBlockIdAt($xx, $y, $zz)])) {
							$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
						}
					}
				}
				for ($i = 0; $i < 2; $i ++) {
					$level->setBlockIdAt($x - 3 - $i, $y, $z, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3 - $i, $y + 3, $z, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3 - $i, $y + 1, $z + 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3 - $i, $y + 1, $z - 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3 - $i, $y + 2, $z + 1, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3 - $i, $y + 2, $z - 1, Block::SNOW_BLOCK);
				}
				for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
					$level->setBlockIdAt($x + 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3, $y + 2, $zz, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					$level->setBlockIdAt($xx, $y + 1, $z - 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z - 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 1, $z + 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z + 3, Block::SNOW_BLOCK);
				}
				$level->setBlockIdAt($x, $y + 1, $z + 3, Block::ICE);
				$level->setBlockIdAt($x, $y + 1, $z - 3, Block::ICE);
				for ($i = 1; $i <= 2; $i ++) {
					$level->setBlockIdAt($x + 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
				}
				for ($i = 0; $i < 3; $i ++) {
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 4, $zz, Block::SNOW_BLOCK);
						$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
					}
				}
				for ($xx = $x - 1; $xx <= $x + 2; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 1, $zz, Block::CARPET);
					}
				}
				$level->setBlockIdAt($x + 1, $y + 1, $z + 2, Block::BED_BLOCK);
				$level->setBlockIdAt($x, $y + 1, $z + 2, Block::BED_BLOCK);
				$level->setBlockDataAt($x + 1, $y + 1, $z + 2, 11);
				$level->setBlockDataAt($x, $y + 1, $z + 2, 3);
				$level->setBlockIdAt($x + 1, $y + 1, $z - 2, Block::CRAFTING_TABLE);
				$level->setBlockIdAt($x, $y + 1, $z - 2, Block::REDSTONE_TORCH);
				$level->setBlockIdAt($x - 1, $y + 1, $z - 2, Block::FURNACE);
				break;
			case 2:
				for ($xx = $x - 3; $xx <= $x + 3; $xx ++) {
					for ($zz = $z - 3; $zz <= $z + 4; $zz ++) {
						if (! isset($this->overridable[$level->getBlockIdAt($xx, $y, $zz)])) {
							$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
						}
					}
				}
				for ($i = 0; $i < 2; $i ++) {
					$level->setBlockIdAt($x, $y, $z + 3 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x, $y + 3, $z + 3 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 1, $y + 1, $z + 3 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1, $y + 1, $z + 3 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 1, $y + 2, $z + 3 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1, $y + 2, $z + 3 + $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					$level->setBlockIdAt($xx, $y + 1, $z - 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z - 3, Block::SNOW_BLOCK);
				}
				for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
					$level->setBlockIdAt($x - 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3, $y + 2, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3, $y + 2, $zz, Block::SNOW_BLOCK);
				}
				$level->setBlockIdAt($x + 3, $y + 1, $z, Block::ICE);
				$level->setBlockIdAt($x - 3, $y + 1, $z, Block::ICE);
				for ($i = 1; $i <= 2; $i ++) {
					$level->setBlockIdAt($x + 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
				}
				for ($i = 0; $i < 3; $i ++) {
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 4, $zz, Block::SNOW_BLOCK);
						$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
					}
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 2; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 1, $zz, Block::CARPET);
					}
				}
				$level->setBlockIdAt($x + 2, $y + 1, $z - 1, Block::BED_BLOCK);
				$level->setBlockIdAt($x + 2, $y + 1, $z, Block::BED_BLOCK);
				$level->setBlockDataAt($x + 2, $y + 1, $z - 1, 10);
				$level->setBlockDataAt($x + 2, $y + 1, $z, 2);
				$level->setBlockIdAt($x - 2, $y + 1, $z + 1, Block::CRAFTING_TABLE);
				$level->setBlockIdAt($x - 2, $y + 1, $z, Block::REDSTONE_TORCH);
				$level->setBlockIdAt($x - 2, $y + 1, $z - 1, Block::FURNACE);
				break;
			case 3:
				for ($xx = $x - 3; $xx <= $x + 3; $xx ++) {
					for ($zz = $z - 4; $zz <= $z + 3; $zz ++) {
						if (! isset($this->overridable[$level->getBlockIdAt($xx, $y, $zz)])) {
							$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
						}
					}
				}
				for ($i = 0; $i < 2; $i ++) {
					$level->setBlockIdAt($x, $y, $z - 3 - $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x, $y + 3, $z - 3 - $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 1, $y + 1, $z - 3 - $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1, $y + 1, $z - 3 - $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 1, $y + 2, $z - 3 - $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1, $y + 2, $z - 3 - $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					$level->setBlockIdAt($xx, $y + 1, $z + 3, Block::SNOW_BLOCK);
					$level->setBlockIdAt($xx, $y + 2, $z + 3, Block::SNOW_BLOCK);
				}
				for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
					$level->setBlockIdAt($x - 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 3, $y + 2, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3, $y + 1, $zz, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 3, $y + 2, $zz, Block::SNOW_BLOCK);
				}
				$level->setBlockIdAt($x + 3, $y + 1, $z, Block::ICE);
				$level->setBlockIdAt($x - 3, $y + 1, $z, Block::ICE);
				for ($i = 1; $i <= 2; $i ++) {
					$level->setBlockIdAt($x + 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + $i, $z - 2, Block::SNOW_BLOCK);
				}
				for ($i = 0; $i < 3; $i ++) {
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z + 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 1 + $i, $y + 3, $z - 2, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x + 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
					$level->setBlockIdAt($x - 2, $y + 3, $z - 1 + $i, Block::SNOW_BLOCK);
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 1; $zz ++) {
						$level->setBlockIdAt($xx, $y + 4, $zz, Block::SNOW_BLOCK);
						$level->setBlockIdAt($xx, $y, $zz, Block::SNOW_BLOCK);
					}
				}
				for ($xx = $x - 1; $xx <= $x + 1; $xx ++) {
					for ($zz = $z - 1; $zz <= $z + 2; $zz ++) {
						$level->setBlockIdAt($xx, $y + 1, $zz, Block::CARPET);
					}
				}
				$level->setBlockIdAt($x + 2, $y + 1, $z + 1, Block::BED_BLOCK);
				$level->setBlockIdAt($x + 2, $y + 1, $z, Block::BED_BLOCK);
				$level->setBlockDataAt($x + 2, $y + 1, $z + 1, 8);
				$level->setBlockDataAt($x + 2, $y + 1, $z, 0);
				$level->setBlockIdAt($x - 2, $y + 1, $z - 1, Block::CRAFTING_TABLE);
				$level->setBlockIdAt($x - 2, $y + 1, $z, Block::REDSTONE_TORCH);
				$level->setBlockIdAt($x - 2, $y + 1, $z + 1, Block::FURNACE);
				break;
		}
		return true;
	}

}
