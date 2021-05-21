<?php

namespace pocketmine\level\generator\structure;

use pocketmine\level\generator\utils\BuildingUtils;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Temple extends PopulatorObject {

	const DIRECTION_PLUSX = 0;
	const DIRECTION_MINX = 1;
	const DIRECTION_PLUSZ = 2;
	const DIRECTION_MINZ = 3;
	const THREE_DIAGS = [
		[
			3,
			0,
		],
		[
			0,
			3,
		],
		[
			2,
			1,
		],
		[
			1,
			2,
		],
		[
			-3,
			0,
		],
		[
			-2,
			1,
		],
		[
			-1,
			2,
		],
		[
			0,
			-3,
		],
		[
			2,
			-1,
		],
		[
			1,
			-2,
		],
		[
			-2,
			-1,
		],
		[
			-1,
			-2,
		]
	];

	public $overridable = [
		Block::AIR => true,
		Block::SAPLING => true,
		Block::LOG => true,
		Block::LEAVES => true,
		Block::STONE => true,
		Block::DANDELION => true,
		Block::POPPY => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
		Block::LEAVES2 => true,
		Block::CACTUS => true,
	];

	protected $directions = [
		[
			1,
			1,
		],
		[
			1,
			-1,
		],
		[
			-1,
			-1,
		],
		[
			-1,
			1,
		]
	];

	protected $level;
	protected $direction = 0;

	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->level = $level;
		$this->direction = $random->nextBoundedInt(4);
		for ($xx = $x - 10; $xx <= $x + 10; $xx++) {
			for ($yy = $y + 1; $yy <= $y + 11; $yy++) {
				for ($zz = $z - 10; $zz <= $z + 10; $zz++) {
					if (!isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)])) {
						return false;
					}
				}
			}
		}
		return true;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		BuildingUtils::fill($level, new Vector3($x + 10, $y + 1, $z + 10), new Vector3($x - 10, $y + 2, $z - 10), Block::get(Block::AIR));
		$this->level = $level;
		$firstPos = new Vector3($x + 10, $y, $z + 10);
		$sndPos = new Vector3($x - 10, $y, $z - 10);

		for ($i = 0; $i <= 9; $i++) {
			BuildingUtils::walls($level, $firstPos->add(-$i, $i, -$i), $sndPos->add($i, $i, $i), Block::get(Block::SANDSTONE));
		}

		BuildingUtils::fill($level, new Vector3($x - 5, $y + 4, $z - 5), new Vector3($x + 5, $y + 4, $z + 5), Block::get(Block::SANDSTONE));
		BuildingUtils::fill($level, new Vector3($x - 1, $y - 11, $z - 1), new Vector3($x + 1, $y + 4, $z + 1), Block::get(Block::AIR));
		BuildingUtils::walls($level, new Vector3($x - 2, $y - 1, $z - 2), new Vector3($x + 2, $y - 8, $z + 2), Block::get(Block::SANDSTONE));
		BuildingUtils::fill($level, new Vector3($x - 9, $y, $z - 9), new Vector3($x + 9, $y, $z + 9), Block::get(Block::SANDSTONE));

		for ($i = -2; $i <= 1; $i++) {
			$xextra = ($i + 1) % 2;
			$zextra = ($i) % 2;
			$this->placeBlock($x + ($xextra * 3), $y, $z + ($zextra * 3), Block::STAINED_HARDENED_CLAY, 1);
			$this->placeBlock($x + ($xextra * 2), $y, $z + ($zextra * 2), Block::STAINED_HARDENED_CLAY, 1);
		}
		foreach ($this->directions as $direction) {
			for ($yy = $y + 1; $yy <= $y + 3; $yy++)
				$this->placeBlock($x + ($direction[0] * 2), $yy, $z + ($direction[1] * 2), Block::SANDSTONE, 2);
			$this->placeBlock($x + $direction[0], $y, $z + $direction[1], Block::STAINED_HARDENED_CLAY, 1);//Diagonal
		}

		$this->placeBlock($x, $y, $z, Block::STAINED_HARDENED_CLAY, 11);

		for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
			$this->placeBlock($xx, $y - 9, $z - 2, Block::SANDSTONE, 2);
			$this->placeBlock($xx, $y - 9, $z + 2, Block::SANDSTONE, 2);
		}
		for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
			$this->placeBlock($x - 2, $y - 9, $zz, Block::SANDSTONE, 2);
			$this->placeBlock($x + 2, $y - 9, $zz, Block::SANDSTONE, 2);
		}

		foreach (self::THREE_DIAGS as $diagPos) {
			$this->placeBlock($x + $diagPos[0], $y - 10, $z + $diagPos[1], Block::SANDSTONE, 1);
			$this->placeBlock($x + $diagPos[0], $y - 11, $z + $diagPos[1], Block::SANDSTONE, 2);
		}

		for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
			for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
				$this->placeBlock($xx, $y - 12, $zz, Block::SANDSTONE, 2);
			}
		}
		for ($xx = $x - 1; $xx <= $x + 1; $xx++) {
			for ($zz = $z - 1; $zz <= $z + 1; $zz++) {
				$this->placeBlock($xx, $y - 13, $zz, Block::TNT);
			}
		}
		$this->placeBlock($x, $y - 11, $z, Block::STONE_PRESSURE_PLATE);

		$this->placeBlock($x, $y - 11, $z + 2, Block::CHEST, 4);
		$this->placeBlock($x, $y - 11, $z - 2, Block::CHEST, 2);
		$this->placeBlock($x + 2, $y - 11, $z, Block::CHEST, 5);
		$this->placeBlock($x - 2, $y - 11, $z, Block::CHEST, 3);
		$this->placeBlock($x, $y - 10, $z + 2, Block::AIR);
		$this->placeBlock($x, $y - 10, $z - 2, Block::AIR);
		$this->placeBlock($x + 2, $y - 10, $z, Block::AIR);
		$this->placeBlock($x - 2, $y - 10, $z, Block::AIR);

//		LootTable::buildLootTable(new Vector3($x, $y - 11, $z + 2), LootTable::LOOT_DESERT_TEMPLE, $random);
//		LootTable::buildLootTable(new Vector3($x, $y - 11, $z - 2), LootTable::LOOT_DESERT_TEMPLE, $random);
//		LootTable::buildLootTable(new Vector3($x + 2, $y - 11, $z), LootTable::LOOT_DESERT_TEMPLE, $random);
//		LootTable::buildLootTable(new Vector3($x - 2, $y - 11, $z), LootTable::LOOT_DESERT_TEMPLE, $random);

		switch ($this->direction) {
			case self::DIRECTION_PLUSX:
				$this->placeTower($x + 8, $y, $z + 8, self::DIRECTION_PLUSX, self::DIRECTION_PLUSZ);
				$this->placeTower($x + 8, $y, $z - 8, self::DIRECTION_PLUSX, self::DIRECTION_MINZ);

				BuildingUtils::fill($level, new Vector3($x + 6, $y + 1, $z - 6), new Vector3($x + 9, $y + 4, $z + 6), Block::get(Block::SANDSTONE));
				BuildingUtils::fill($level, new Vector3($x + 6, $y + 1, $z - 1), new Vector3($x + 9, $y + 4, $z + 1), Block::get(Block::AIR));//this clears the entrance

				for ($yy = $y + 1; $yy <= $y + 2; $yy++) {
					for ($zz = $z - 6; $zz <= $z + 6; $zz++) {
						$this->placeBlock($x + 8, $yy, $zz, 0);
					}
				}

				for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
					$this->placeBlock($x + 6, $yy, $z - 2);
					$this->placeBlock($x + 6, $yy, $z + 2);
					$this->placeBlock($x + 9, $yy, $z + 1, Block::SANDSTONE, 2);
					$this->placeBlock($x + 9, $yy, $z - 1, Block::SANDSTONE, 2);
					$this->placeBlock($x + 10, $yy, $z - 2);
					$this->placeBlock($x + 10, $yy, $z + 2);
				}

				$this->placeBlock($x + 9, $y + 3, $z, Block::SANDSTONE, 2);

				for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
					$this->placeBlock($x + 10, $y + 4, $zz, Block::SANDSTONE, 2);
				}
				$this->placeBlock($x + 10, $y + 5, $z, Block::SANDSTONE, 1);
				$this->placeBlock($x + 10, $y + 5, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 10, $y + 5, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 10, $y + 5, $z - 2, Block::SANDSTONE, 2);
				$this->placeBlock($x + 10, $y + 5, $z + 2, Block::SANDSTONE, 2);
				for ($zz = $z - 1; $zz <= $z + 1; $zz++) {
					$this->placeBlock($x + 10, $y + 6, $zz, Block::SANDSTONE, 2);
				}
				for ($xx = $x + 6; $xx <= $x + 9; $xx++) {
					for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
						$this->placeBlock($xx, $y + 4, $zz);
					}
				}
				break;
			case self::DIRECTION_MINX:
				$this->placeTower($x - 8, $y, $z + 8, self::DIRECTION_MINX, self::DIRECTION_PLUSZ);
				$this->placeTower($x - 8, $y, $z - 8, self::DIRECTION_MINX, self::DIRECTION_MINZ);

				for ($xx = $x - 6; $xx >= $x - 9; $xx--) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
						for ($zz = $z - 6; $zz <= $z + 6; $zz++) {
							$this->placeBlock($xx, $yy, $zz);
						}
					}
				}

				for ($xx = $x - 6; $xx >= $x - 9; $xx--) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
						for ($zz = $z - 1; $zz <= $z + 1; $zz++) {
							$this->placeBlock($xx, $yy, $zz, 0);
						}
					}
				}

				for ($yy = $y + 1; $yy <= $y + 2; $yy++) {
					for ($zz = $z - 6; $zz <= $z + 6; $zz++) {
						$this->placeBlock($x - 8, $yy, $zz, 0);
					}
				}

				for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
					$this->placeBlock($x - 6, $yy, $z - 2);
					$this->placeBlock($x - 6, $yy, $z + 2);
					$this->placeBlock($x - 9, $yy, $z + 1, Block::SANDSTONE, 2);
					$this->placeBlock($x - 9, $yy, $z - 1, Block::SANDSTONE, 2);
					$this->placeBlock($x - 10, $yy, $z - 2);
					$this->placeBlock($x - 10, $yy, $z + 2);
				}

				$this->placeBlock($x - 9, $y + 3, $z, Block::SANDSTONE, 2);

				for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
					$this->placeBlock($x - 10, $y + 4, $zz, Block::SANDSTONE, 2);
				}
				$this->placeBlock($x - 10, $y + 5, $z, Block::SANDSTONE, 1);
				$this->placeBlock($x - 10, $y + 5, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 10, $y + 5, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 10, $y + 5, $z - 2, Block::SANDSTONE, 2);
				$this->placeBlock($x - 10, $y + 5, $z + 2, Block::SANDSTONE, 2);

				for ($zz = $z - 1; $zz <= $z + 1; $zz++) {
					$this->placeBlock($x - 10, $y + 6, $zz, Block::SANDSTONE, 2);
				}

				for ($xx = $x - 6; $xx >= $x - 9; $xx--) {
					for ($zz = $z - 2; $zz <= $z + 2; $zz++) {
						$this->placeBlock($xx, $y + 4, $zz);
					}
				}
				break;
			case self::DIRECTION_PLUSZ:
				$this->placeTower($x + 8, $y, $z + 8, self::DIRECTION_PLUSZ, self::DIRECTION_PLUSX);
				$this->placeTower($x - 8, $y, $z + 8, self::DIRECTION_PLUSZ, self::DIRECTION_MINX);

				BuildingUtils::fill($level, new Vector3($x - 6, $y + 1, $z + 6), new Vector3($x + 6, $y + 4, $z + 9), Block::get(Block::SANDSTONE));

				for ($xx = $x - 1; $xx <= $x + 1; $xx++) {
					for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
						for ($zz = $z + 6; $zz <= $z + 9; $zz++) {
							$this->placeBlock($xx, $yy, $zz, 0);
						}
					}
				}

				BuildingUtils::fill($level, new Vector3($x - 1, $y + 1, $z + 6), new Vector3($x + 1, $y + 4, $z + 9), Block::get(Block::AIR));

				for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
					$this->placeBlock($x - 2, $yy, $z + 6);
					$this->placeBlock($x + 2, $yy, $z + 6);
					$this->placeBlock($x + 1, $yy, $z + 9, Block::SANDSTONE, 2);
					$this->placeBlock($x - 1, $yy, $z + 9, Block::SANDSTONE, 2);
					$this->placeBlock($x + 2, $yy, $z + 10);
					$this->placeBlock($x - 2, $yy, $z + 10);
				}

				$this->placeBlock($x, $y + 3, $z + 9, Block::SANDSTONE, 2);

				for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
					$this->placeBlock($xx, $y + 4, $z + 10, Block::SANDSTONE, 2);
				}

				$this->placeBlock($x, $y + 5, $z + 10, Block::SANDSTONE, 1);
				$this->placeBlock($x - 1, $y + 5, $z + 10, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 1, $y + 5, $z + 10, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 2, $y + 5, $z + 10, Block::SANDSTONE, 2);
				$this->placeBlock($x + 2, $y + 5, $z + 10, Block::SANDSTONE, 2);

				for ($xx = $x - 1; $xx <= $x + 1; $xx++) {
					$this->placeBlock($xx, $y + 6, $z + 10, Block::SANDSTONE, 2);
				}

				for ($zz = $z + 6; $zz <= $z + 9; $zz++) {
					for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
						$this->placeBlock($xx, $y + 4, $zz);
					}
				}
				break;
			case self::DIRECTION_MINZ:
				$this->placeTower($x + 8, $y, $z - 8, self::DIRECTION_MINZ, self::DIRECTION_PLUSX);
				$this->placeTower($x - 8, $y, $z - 8, self::DIRECTION_MINZ, self::DIRECTION_MINX);

				BuildingUtils::fill($level, new Vector3($x - 6, $y + 1, $z - 6), new Vector3($x + 6, $y + 4, $z - 9), Block::get(Block::SANDSTONE));
				BuildingUtils::fill($level, new Vector3($x - 1, $y + 1, $z - 6), new Vector3($x + 1, $y + 4, $z - 9), Block::get(Block::AIR));

				for ($yy = $y + 1; $yy <= $y + 2; $yy++) {
					for ($xx = $x - 6; $xx <= $x + 6; $xx++) {
						$this->placeBlock($xx, $yy, $z - 8, 0);
					}
				}

				for ($yy = $y + 1; $yy <= $y + 4; $yy++) {
					$this->placeBlock($x - 2, $yy, $z - 6);
					$this->placeBlock($x + 2, $yy, $z - 6);
					$this->placeBlock($x + 1, $yy, $z - 9, Block::SANDSTONE, 2);
					$this->placeBlock($x - 1, $yy, $z - 9, Block::SANDSTONE, 2);
					$this->placeBlock($x + 2, $yy, $z - 10);
					$this->placeBlock($x - 2, $yy, $z - 10);
				}

				$this->placeBlock($x, $y + 3, $z - 9, Block::SANDSTONE, 2);

				for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
					$this->placeBlock($xx, $y + 4, $z - 10, Block::SANDSTONE, 2);
				}

				$this->placeBlock($x, $y + 5, $z - 10, Block::SANDSTONE, 1);
				$this->placeBlock($x - 1, $y + 5, $z - 10, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 1, $y + 5, $z - 10, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 2, $y + 5, $z - 10, Block::SANDSTONE, 2);
				$this->placeBlock($x + 2, $y + 5, $z - 10, Block::SANDSTONE, 2);

				for ($xx = $x - 1; $xx <= $x + 1; $xx++) {
					$this->placeBlock($xx, $y + 6, $z - 10, Block::SANDSTONE, 2);
				}

				for ($zz = $z - 6; $zz >= $z - 9; $zz--) {
					for ($xx = $x - 2; $xx <= $x + 2; $xx++) {
						$this->placeBlock($xx, $y + 4, $zz);
					}
				}
				break;
		}
	}

	protected function placeBlock($x, $y, $z, $id = Block::SANDSTONE, $meta = 0) {
		$this->level->setBlockIdAt($x, $y, $z, $id);
		$this->level->setBlockDataAt($x, $y, $z, $meta);
	}

	public function placeTower($x, $y, $z, $direction1 = self::DIRECTION_PLUSX, $direction2 = self::DIRECTION_PLUSZ) {
		BuildingUtils::walls($this->level, new Vector3($x + 2, $y, $z + 2), new Vector3($x - 2, $y + 8, $z - 2), Block::get(Block::SANDSTONE));
		BuildingUtils::fill($this->level, new Vector3($x + 1, $y + 1, $z + 1), new Vector3($x - 1, $y + 7, $z - 1), Block::get(Block::AIR));
		switch ($direction1) {
			case self::DIRECTION_PLUSX:
				switch ($direction2) {
					case self::DIRECTION_PLUSZ :
						for ($zz = $z + 1; $zz >= $z; $zz--) {
							$this->placeBlock($x - 1, $y + 1, $zz);
							$this->placeBlock($x - 1, $y + 2, $zz);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 2);
						$this->placeBlock($x, $y + 1, $z + 1);
						$this->placeSlab($x, $y + 2, $z + 1);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x - 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
							$this->placeBlock($x + 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
							$this->placeBlock($x, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x - 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x, $y + $h, $z + 2, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 1, $y + 7, $z + 2, Block::SANDSTONE, 2);
						$this->placeBlock($x, $y + 7, $z + 2, Block::SANDSTONE, 2);
						$this->placeBlock($x + 1, $y + 7, $z + 2, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x - 9, $y + 5, $z - 4), new Vector3($x - 7, $y + 7, $z - 5), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x - 8, $y + 5, $z - 4), new Vector3($x - 8, $y + 6, $z - 5), Block::get(Block::AIR));
						break;
					case self::DIRECTION_MINZ:
						for ($zz = $z - 1; $zz <= $z; $zz++) {
							$this->placeBlock($x - 1, $y + 1, $zz);
							$this->placeBlock($x - 1, $y + 2, $zz);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 3);
						$this->placeBlock($x, $y + 1, $z - 1);
						$this->placeSlab($x, $y + 2, $z - 1);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x - 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
							$this->placeBlock($x + 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
							$this->placeBlock($x, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x - 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x, $y + $h, $z - 2, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 1, $y + 7, $z - 2, Block::SANDSTONE, 2);
						$this->placeBlock($x, $y + 7, $z - 2, Block::SANDSTONE, 2);
						$this->placeBlock($x + 1, $y + 7, $z - 2, Block::SANDSTONE, 2);
						break;
				}

				BuildingUtils::fill($this->level, new Vector3($x - 9, $y + 5, $z + 4), new Vector3($x - 7, $y + 7, $z + 5), Block::get(Block::SANDSTONE, 2));
				BuildingUtils::fill($this->level, new Vector3($x - 8, $y + 5, $z + 4), new Vector3($x - 8, $y + 6, $z + 5), Block::get(Block::AIR));

				$this->placeBlock($x - 2, $y + 3, $z, Block::SANDSTONE_STAIRS, 1);
				$this->placeBlock($x - 3, $y + 4, $z, Block::SANDSTONE_STAIRS, 1);
				$this->placeBlock($x - 2, $y + 4, $z, Block::AIR);
				$this->placeBlock($x - 2, $y + 5, $z, Block::AIR);
				$this->placeBlock($x - 2, $y + 6, $z, Block::AIR);

				BuildingUtils::fill($this->level, new Vector3($x - 3, $y, $z + 1 + ($direction2 === self::DIRECTION_PLUSZ ? 2 : 0)), new Vector3($x - 8, $y + 4, $z - 1 + ($direction2 === self::DIRECTION_MINZ ? -2 : 0)), Block::get(Block::SANDSTONE));

				foreach ([1, 2, 4] as $h) {
					$this->placeBlock($x + 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
					$this->placeBlock($x + 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
					$this->placeBlock($x + 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
				}
				foreach ([3, 5] as $h) {
					$this->placeBlock($x + 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x + 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x + 2, $y + $h, $z, Block::SANDSTONE, 1);
				}
				$this->placeBlock($x + 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
				$this->placeBlock($x + 2, $y + 7, $z, Block::SANDSTONE, 2);
				$this->placeBlock($x + 2, $y + 7, $z + 1, Block::SANDSTONE, 2);
				break;
			case self::DIRECTION_MINX:
				switch ($direction2) {
					case self::DIRECTION_PLUSZ:
						for ($zz = $z + 1; $zz >= $z; $zz--) {
							$this->placeBlock($x + 1, $y + 1, $zz);
							$this->placeBlock($x + 1, $y + 2, $zz);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 2);
						$this->placeBlock($x, $y + 1, $z + 1);
						$this->placeSlab($x, $y + 2, $z + 1);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x + 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
							$this->placeBlock($x - 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
							$this->placeBlock($x, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x + 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x, $y + $h, $z + 2, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 1, $y + 7, $z + 2, Block::SANDSTONE, 2);
						$this->placeBlock($x, $y + 7, $z + 2, Block::SANDSTONE, 2);
						$this->placeBlock($x + 1, $y + 7, $z + 2, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x + 9, $y + 5, $z - 4), new Vector3($x + 7, $y + 7, $z - 5), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x + 8, $y + 5, $z - 4), new Vector3($x + 8, $y + 6, $z - 5), Block::get(Block::AIR));
						break;
					case self::DIRECTION_MINZ:
						for ($zz = $z - 1; $zz <= $z; $zz++) {
							$this->placeBlock($x + 1, $y + 1, $zz);
							$this->placeBlock($x + 1, $y + 2, $zz);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 3);
						$this->placeBlock($x, $y + 1, $z - 1);
						$this->placeSlab($x, $y + 2, $z - 1);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x + 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
							$this->placeBlock($x - 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
							$this->placeBlock($x, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x + 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x, $y + $h, $z - 2, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 1, $y + 6, $z - 2, Block::SANDSTONE, 2);
						$this->placeBlock($x, $y + 6, $z - 2, Block::SANDSTONE, 2);
						$this->placeBlock($x + 1, $y + 6, $z - 2, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x + 9, $y + 5, $z + 4), new Vector3($x + 7, $y + 7, $z + 5), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x + 8, $y + 5, $z + 4), new Vector3($x + 8, $y + 6, $z + 5), Block::get(Block::AIR));
						break;
				}

				$this->placeBlock($x + 2, $y + 3, $z, Block::SANDSTONE_STAIRS, 0);
				$this->placeBlock($x + 3, $y + 4, $z, Block::SANDSTONE_STAIRS, 0);
				$this->placeBlock($x + 2, $y + 4, $z, Block::AIR);
				$this->placeBlock($x + 2, $y + 5, $z, Block::AIR);
				$this->placeBlock($x + 2, $y + 6, $z, Block::AIR);

				BuildingUtils::fill($this->level, new Vector3($x + 3, $y, $z + 1 + ($direction2 === self::DIRECTION_PLUSZ ? 2 : 0)), new Vector3($x + 8, $y + 4, $z - 1 + ($direction2 === self::DIRECTION_MINZ ? -2 : 0)), Block::get(Block::SANDSTONE));

				foreach ([1, 2, 4] as $h) {
					$this->placeBlock($x - 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
					$this->placeBlock($x - 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
					$this->placeBlock($x - 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
				}
				foreach ([3, 5] as $h) {
					$this->placeBlock($x - 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x - 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x - 2, $y + $h, $z, Block::SANDSTONE, 1);
				}
				$this->placeBlock($x - 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
				$this->placeBlock($x - 2, $y + 7, $z, Block::SANDSTONE, 2);
				$this->placeBlock($x - 2, $y + 7, $z + 1, Block::SANDSTONE, 2);
				break;
			case self::DIRECTION_PLUSZ:
				switch ($direction2) {
					case self::DIRECTION_PLUSX:
						for ($xx = $x + 1; $xx >= $x; $xx--) {
							$this->placeBlock($xx, $y + 1, $z - 1);
							$this->placeBlock($xx, $y + 2, $z - 1);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 0);
						$this->placeBlock($x + 1, $y + 1, $z);
						$this->placeSlab($x + 1, $y + 2, $z);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x + 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
							$this->placeBlock($x + 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
							$this->placeBlock($x + 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x + 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 2, $y + $h, $z, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x + 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
						$this->placeBlock($x + 2, $y + 7, $z, Block::SANDSTONE, 2);
						$this->placeBlock($x + 2, $y + 7, $z + 1, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x - 4, $y + 5, $z - 9), new Vector3($x - 5, $y + 7, $z - 7), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x - 4, $y + 5, $z - 8), new Vector3($x - 5, $y + 6, $z - 8), Block::get(Block::AIR));
						break;
					case self::DIRECTION_MINX:
						for ($xx = $x - 1; $xx <= $x; $xx++) {
							$this->placeBlock($xx, $y + 1, $z - 1);
							$this->placeBlock($xx, $y + 2, $z - 1);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 1);
						$this->placeBlock($x - 1, $y + 1, $z);
						$this->placeSlab($x - 1, $y + 2, $z);

						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x - 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
							$this->placeBlock($x - 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
							$this->placeBlock($x - 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x - 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 2, $y + $h, $z, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
						$this->placeBlock($x - 2, $y + 7, $z, Block::SANDSTONE, 2);
						$this->placeBlock($x - 2, $y + 7, $z + 1, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x + 4, $y + 5, $z - 9), new Vector3($x + 5, $y + 7, $z - 7), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x + 4, $y + 5, $z - 8), new Vector3($x + 5, $y + 6, $z - 8), Block::get(Block::AIR));
						break;
				}

				$this->placeBlock($x, $y + 3, $z - 2, Block::SANDSTONE_STAIRS, 3);
				$this->placeBlock($x, $y + 4, $z - 3, Block::SANDSTONE_STAIRS, 3);
				$this->placeBlock($x, $y + 4, $z - 2, Block::AIR);
				$this->placeBlock($x, $y + 5, $z - 2, Block::AIR);
				$this->placeBlock($x, $y + 6, $z - 2, Block::AIR);

				BuildingUtils::fill($this->level, new Vector3($x + 1 + ($direction2 === self::DIRECTION_PLUSX ? 2 : 0), $y, $z - 3), new Vector3($x - 1 + ($direction2 === self::DIRECTION_MINX ? -2 : 0), $y + 4, $z - 8), Block::get(Block::SANDSTONE));

				foreach ([1, 2, 4] as $h) {
					$this->placeBlock($x + 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
					$this->placeBlock($x - 1, $y + $h, $z + 2, Block::SANDSTONE, 2);
					$this->placeBlock($x, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
				}
				foreach ([3, 5] as $h) {
					$this->placeBlock($x + 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x - 1, $y + $h, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x, $y + $h, $z + 2, Block::SANDSTONE, 1);
				}
				$this->placeBlock($x - 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 1, $y + 6, $z + 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 1, $y + 7, $z + 2, Block::SANDSTONE, 2);
				$this->placeBlock($x, $y + 7, $z + 2, Block::SANDSTONE, 2);
				$this->placeBlock($x + 1, $y + 7, $z + 2, Block::SANDSTONE, 2);
				break;
			case self::DIRECTION_MINZ:
				switch ($direction2) {
					case self::DIRECTION_PLUSX :
						for ($xx = $x + 1; $xx >= $x; $xx--) {
							$this->placeBlock($xx, $y + 1, $z + 1);
							$this->placeBlock($xx, $y + 2, $z + 1);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 0);
						$this->placeBlock($x + 1, $y + 1, $z);
						$this->placeSlab($x + 1, $y + 2, $z);
						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x + 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
							$this->placeBlock($x + 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
							$this->placeBlock($x + 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x + 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x + 2, $y + $h, $z, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x + 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x + 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
						$this->placeBlock($x + 2, $y + 7, $z, Block::SANDSTONE, 2);
						$this->placeBlock($x + 2, $y + 7, $z + 1, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x - 4, $y + 5, $z + 9), new Vector3($x - 5, $y + 7, $z + 7), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x - 4, $y + 5, $z + 8), new Vector3($x - 5, $y + 6, $z + 8), Block::get(Block::AIR));
						break;
					case self::DIRECTION_MINX:
						for ($xx = $x - 1; $xx <= $x; $xx++) {
							$this->placeBlock($xx, $y + 1, $z + 1);
							$this->placeBlock($xx, $y + 2, $z + 1);
						}
						$this->placeBlock($x, $y + 1, $z, Block::SANDSTONE_STAIRS, 1);
						$this->placeBlock($x - 1, $y + 1, $z);
						$this->placeSlab($x - 1, $y + 2, $z);

						foreach ([1, 2, 4] as $h) {
							$this->placeBlock($x - 2, $y + $h, $z - 1, Block::SANDSTONE, 2);
							$this->placeBlock($x - 2, $y + $h, $z + 1, Block::SANDSTONE, 2);
							$this->placeBlock($x - 2, $y + $h, $z, Block::STAINED_HARDENED_CLAY, 1);
						}
						foreach ([3, 5] as $h) {
							$this->placeBlock($x - 2, $y + $h, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 2, $y + $h, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
							$this->placeBlock($x - 2, $y + $h, $z, Block::SANDSTONE, 1);
						}
						$this->placeBlock($x - 2, $y + 6, $z - 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 6, $z, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 6, $z + 1, Block::STAINED_HARDENED_CLAY, 1);
						$this->placeBlock($x - 2, $y + 7, $z - 1, Block::SANDSTONE, 2);
						$this->placeBlock($x - 2, $y + 7, $z, Block::SANDSTONE, 2);
						$this->placeBlock($x - 2, $y + 7, $z + 1, Block::SANDSTONE, 2);

						BuildingUtils::fill($this->level, new Vector3($x + 4, $y + 5, $z + 9), new Vector3($x + 5, $y + 7, $z + 7), Block::get(Block::SANDSTONE, 2));
						BuildingUtils::fill($this->level, new Vector3($x + 4, $y + 5, $z + 8), new Vector3($x + 5, $y + 6, $z + 8), Block::get(Block::AIR));
						break;
				}

				$this->placeBlock($x, $y + 3, $z + 2, Block::SANDSTONE_STAIRS, 2);
				$this->placeBlock($x, $y + 4, $z + 3, Block::SANDSTONE_STAIRS, 2);
				$this->placeBlock($x, $y + 4, $z + 2, Block::AIR);
				$this->placeBlock($x, $y + 5, $z + 2, Block::AIR);
				$this->placeBlock($x, $y + 6, $z + 2, Block::AIR);

				BuildingUtils::fill($this->level, new Vector3($x + 1 + ($direction2 === self::DIRECTION_PLUSX ? 2 : 0), $y, $z + 3), new Vector3($x - 1 + ($direction2 === self::DIRECTION_MINX ? -2 : 0), $y + 4, $z + 8), Block::get(Block::SANDSTONE));

				foreach ([1, 2, 4] as $h) {
					$this->placeBlock($x + 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
					$this->placeBlock($x - 1, $y + $h, $z - 2, Block::SANDSTONE, 2);
					$this->placeBlock($x, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
				}
				foreach ([3, 5] as $h) {
					$this->placeBlock($x + 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x - 1, $y + $h, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
					$this->placeBlock($x, $y + $h, $z - 2, Block::SANDSTONE, 1);
				}
				$this->placeBlock($x - 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x + 1, $y + 6, $z - 2, Block::STAINED_HARDENED_CLAY, 1);
				$this->placeBlock($x - 1, $y + 7, $z - 2, Block::SANDSTONE, 2);
				$this->placeBlock($x, $y + 7, $z - 2, Block::SANDSTONE, 2);
				$this->placeBlock($x + 1, $y + 7, $z - 2, Block::SANDSTONE, 2);
				break;
		}

		BuildingUtils::top($this->level, new Vector3($x - 1, $y + 9, $z - 1), new Vector3($x + 1, $y, $z + 1), Block::get(Block::SANDSTONE));
		$this->placeBlock($x - 2, $y + 9, $z, Block::SANDSTONE_STAIRS, 0);
		$this->placeBlock($x + 2, $y + 9, $z, Block::SANDSTONE_STAIRS, 1);
		$this->placeBlock($x, $y + 9, $z - 2, Block::SANDSTONE_STAIRS, 2);
		$this->placeBlock($x, $y + 9, $z + 2, Block::SANDSTONE_STAIRS, 3);
	}

	protected function placeSlab($x, $y, $z, $id = 44, $meta = 1, $top = false) {
		if ($top) {
			$meta &= 0x08;
		}
		$this->placeBlock($x, $y, $z, $id, $meta);
	}

	protected function getInvertedDirection(int $direction): int {
		switch ($direction) {
			case self::DIRECTION_PLUSX:
				return self::DIRECTION_MINX;
				break;
			case self::DIRECTION_MINX:
				return self::DIRECTION_PLUSX;
				break;
			case self::DIRECTION_PLUSZ:
				return self::DIRECTION_MINZ;
				break;
			case self::DIRECTION_MINZ:
				return self::DIRECTION_PLUSZ;
				break;
			default :
				return -1;
				break;
		}
	}

}
