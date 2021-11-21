<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\generator\loot\Loot;
use pocketmine\level\utils\BuildingUtils;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Mineshaft extends Populator {

	const DIR_XPLUS = 0;
	const DIR_XMIN = 1;
	const DIR_ZPLUS = 2;
	const DIR_ZMIN = 3;
	const TYPE_FORWARD = 0;
	const TYPE_CROSSPATH = 1;
	const TYPE_STAIRS = 2;

	private static $DISTANCE = 256;
	private static $VARIATION = 16;
	private static $ODD = 3;
	private static $BASE_Y = 35;
	private static $RAND_Y = 11;

	protected $maxPath;
	protected $level;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		if ($this->getAmount($random) < 100) {
			return;
		}
		$this->level = $level;
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$y = $random->nextRange(5, 50);
		BuildingUtils::fill($level, new Vector3($x - 6, $y, $x - 6), new Vector3($x + 6, $y + 8, $z + 6), Block::get(Block::AIR));
		BuildingUtils::fill($level, new Vector3($x - 6, $y, $x - 6), new Vector3($x + 6, $y, $z + 6), Block::get(Block::DIRT));
		$startingPath = $random->nextBoundedInt(4);
		$this->maxPath = $random->nextBoundedInt(100) + 50;
		foreach (array_fill(0, $startingPath, 1) as $hey) {
			$dir = $random->nextBoundedInt(4);
			switch ($dir) {
				case self::DIR_XPLUS:
					$this->generateMineshaftPart($x + 6, $y + $random->nextBoundedInt(5), $z + $random->nextBoundedInt(12) - 6, $dir, $random);
					break;
				case self::DIR_XMIN:
					$this->generateMineshaftPart($x - 6, $y + $random->nextBoundedInt(5), $z + $random->nextBoundedInt(12) - 6, $dir, $random);
					break;
				case self::DIR_ZPLUS:
					$this->generateMineshaftPart($x + $random->nextBoundedInt(12) - 6, $y + $random->nextBoundedInt(8), $z + 6, $dir, $random);
					break;
				case self::DIR_ZMIN:
					$this->generateMineshaftPart($x + $random->nextBoundedInt(12) - 6, $y + $random->nextBoundedInt(8), $z - 6, $dir, $random);
					break;
			}
		}
	}

	public function generateMineshaftPart(int $x, int $y, int $z, int $dir, Random $random) {
		if ($this->maxPath -- < 1 || $y >= $this->getHighestWorkableBlock($x, $z) - 10) {
			return;
		}
		$type = $random->nextBoundedInt(3);
		$level = $this->level;
		switch ($type) {
			case self::TYPE_FORWARD:
				switch ($dir) {
					case self::DIR_XPLUS:
						BuildingUtils::fill($this->level, new Vector3($x, $y, $z - 1), new Vector3($x + 4, $y + 2, $z + 1), Block::get(Block::AIR));
						BuildingUtils::fillCallback(new Vector3($x, $y - 1, $z - 1), new Vector3($x + 4, $y - 1, $z + 1), function ($v3, ChunkManager $level) {
							if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
							}
						}, $this->level);
						BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x + 4, $y, $z), function ($v3, ChunkManager $level, Random $random) {
							if ($random->nextBoundedInt(3) !== 0) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::RAIL);
								$level->setBlockDataAt($v3->x, $v3->y, $v3->z, 1);
							}
						}, $this->level, $random);
						$level->setBlockIdAt($x, $y, $z - 1, Block::FENCE);
						$level->setBlockIdAt($x, $y, $z + 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 1, $z - 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 1, $z + 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 2, $z - 1, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z + 1, Block::PLANKS);
						$level->setBlockIdAt($x + 1, $y + 2, $z, Block::TORCH);
						$level->setBlockDataAt($x + 1, $y + 2, $z, 2);
						if ($random->nextBoundedInt(30) == 0) {
							$direction =(int) $random->nextBoolean ();
							if ($direction == 0) {
								$direction = -1;
							}
							$direction2 =(int) $random->nextBoolean ();
							if ($direction2 == 0) {
								$direction2 = 2;
							}
							if ($direction2 == 1) {
								$direction2 = 4;
							}
							LootTable::buildLootTable(new Vector3($x + $direction2, $y, $z + $direction), LootTable::LOOT_MINESHAFT, $random);
						}
						if ($random->nextBoundedInt(30) !== 0) {
							$this->generateMineshaftPart($x + 5, $y, $z, $dir, $random);
						}
						break;
					case self::DIR_XMIN:
						BuildingUtils::fill($this->level, new Vector3($x, $y, $z - 1), new Vector3($x - 4, $y + 2, $z + 1));
						BuildingUtils::fillCallback(new Vector3($x, $y - 1, $z - 1), new Vector3($x - 4, $y - 1, $z + 1), function ($v3, ChunkManager $level) {
							if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
							}
						}, $this->level);
						BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x - 4, $y, $z), function ($v3, ChunkManager $level, Random $random) {
							if ($random->nextBoundedInt(3) !== 0) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::RAIL);
								$level->setBlockDataAt($v3->x, $v3->y, $v3->z, 1);
							}
						}, $this->level, $random);
						$level->setBlockIdAt($x, $y, $z - 1, Block::FENCE);
						$level->setBlockIdAt($x, $y, $z + 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 1, $z - 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 1, $z + 1, Block::FENCE);
						$level->setBlockIdAt($x, $y + 2, $z - 1, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z + 1, Block::PLANKS);
						$level->setBlockIdAt($x - 1, $y + 2, $z, Block::TORCH);
						$level->setBlockDataAt($x - 1, $y + 2, $z, 1);
						if ($random->nextBoundedInt(30) == 0) {
							$direction =(int) $random->nextBoolean ();
							if ($direction == 0) {
								$direction = -1;
							}
							$direction2 =(int) $random->nextBoolean ();
							if ($direction2 == 0) {
								$direction2 = 2;
							}
							if ($direction2 == 1) {
								$direction2 = 4;
							}
							LootTable::buildLootTable(new Vector3($x - $direction2, $y, $z + $direction), LootTable::LOOT_MINESHAFT, $random);
						}
						if ($random->nextBoundedInt(30) !== 0) {
							$this->generateMineshaftPart($x - 5, $y, $z, $dir, $random);
						}
						break;
					case self::DIR_ZPLUS:
						BuildingUtils::fill($this->level, new Vector3($x - 1, $y, $z), new Vector3($x + 1, $y + 2, $z + 4));
						BuildingUtils::fillCallback(new Vector3($x - 1, $y - 1, $z), new Vector3($x + 1, $y - 1, $z + 4), function ($v3, ChunkManager $level) {
							if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
							}
						}, $this->level);
						BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x, $y, $z + 4), function ($v3, ChunkManager $level, Random $random) {
							if ($random->nextBoundedInt(3) !== 0) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::RAIL);
								$level->setBlockDataAt($v3->x, $v3->y, $v3->z, 0);
							}
						}, $this->level, $random);
						$level->setBlockIdAt($x - 1, $y, $z, Block::FENCE);
						$level->setBlockIdAt($x + 1, $y, $z, Block::FENCE);
						$level->setBlockIdAt($x - 1, $y + 1, $z, Block::FENCE);
						$level->setBlockIdAt($x + 1, $y + 1, $z, Block::FENCE);
						$level->setBlockIdAt($x - 1, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x + 1, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z - 1, Block::TORCH);
						$level->setBlockDataAt($x, $y + 2, $z - 1, 4);
						if ($random->nextBoundedInt(30) == 0) {
							$direction =(int) $random->nextBoolean ();
							if ($direction == 0) {
								$direction = -1;
							}
							$direction2 =(int) $random->nextBoolean ();
							if ($direction2 == 0) {
								$direction2 = 2;
							}
							if ($direction2 == 1) {
								$direction2 = 4;
							}
							LootTable::buildLootTable(new Vector3($x + $direction, $y, $z + $direction2), LootTable::LOOT_MINESHAFT, $random);
						}
						if ($random->nextBoundedInt(30) !== 0) {
							$this->generateMineshaftPart($x, $y, $z + 5, $dir, $random);
						}
						break;
					case self::DIR_ZMIN:
						BuildingUtils::fill($this->level, new Vector3($x - 1, $y, $z), new Vector3($x + 1, $y + 2, $z - 4));
						BuildingUtils::fillCallback(new Vector3($x - 1, $y - 1, $z), new Vector3($x + 1, $y - 1, $z - 4), function ($v3, ChunkManager $level) {
							if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
							}
						}, $this->level);
						BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x, $y, $z - 4), function ($v3, ChunkManager $level, Random $random) {
							if ($random->nextBoundedInt(3) !== 0) {
								$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::RAIL);
								$level->setBlockDataAt($v3->x, $v3->y, $v3->z, 0);
							}
						}, $this->level, $random);
						$level->setBlockIdAt($x - 1, $y, $z, Block::FENCE);
						$level->setBlockIdAt($x + 1, $y, $z, Block::FENCE);
						$level->setBlockIdAt($x - 1, $y + 1, $z, Block::FENCE);
						$level->setBlockIdAt($x + 1, $y + 1, $z, Block::FENCE);
						$level->setBlockIdAt($x - 1, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x + 1, $y + 2, $z, Block::PLANKS);
						$level->setBlockIdAt($x, $y + 2, $z - 1, Block::TORCH);
						$level->setBlockDataAt($x, $y + 2, $z - 1, 3);
						if ($random->nextBoundedInt(30) == 0) {
							$direction =(int) $random->nextBoolean ();
							if ($direction == 0) {
								$direction = -1;
							}
							$direction2 =(int) $random->nextBoolean ();
							if ($direction2 == 0) {
								$direction2 = 2;
							}
							if ($direction2 == 1) {
								$direction2 = 4;
							}
							LootTable::buildLootTable(new Vector3($x + $direction, $y, $z - $direction2), LootTable::LOOT_MINESHAFT, $random);
						}
						if ($random->nextBoundedInt(30) !== 0) {
							$this->generateMineshaftPart($x, $y, $z - 5, $dir, $random);
						}
						break;
				}
				$webNum = $random->nextBoundedInt(5) + 2;
				for ($i = 0; $i < $webNum; $i ++) {
					$xx = $x + $random->nextBoundedInt(5) - 2;
					$yy = $y + $random->nextBoundedInt(3);
					$zz = $z + $random->nextBoundedInt(5) - 2;
					if ($level->getBlockIdAt($xx, $yy, $zz) == Block::AIR) {
						$level->setBlockIdAt($xx, $yy, $zz, Block::COBWEB);
					}
				}
				break;
			case self::TYPE_CROSSPATH:
				$possiblePathes = [
					self::DIR_XPLUS,
					self::DIR_XMIN,
					self::DIR_ZPLUS,
					self::DIR_ZMIN,
				];
				switch ($dir) {
					case self::DIR_XPLUS:
						$x ++;
						unset($possiblePathes[0]);
						break;
					case self::DIR_XMIN:
						$x --;
						unset($possiblePathes[1]);
						break;
					case self::DIR_ZPLUS:
						$z ++;
						unset($possiblePathes[2]);
						break;
					case self::DIR_ZMIN:
						$z --;
						unset($possiblePathes[3]);
						break;
				}

				BuildingUtils::fillCallback(new Vector3($x + 1, $y - 1, $z - 1), new Vector3($x - 1, $y - 1, $z + 1), function ($v3, ChunkManager $level) {
					if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
						$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
					}
				}, $this->level);

				BuildingUtils::fill($this->level, new Vector3($x - 1, $y, $z - 1), new Vector3($x + 1, $y + 6, $z + 1), Block::get(Block::AIR));

				BuildingUtils::corners($this->level, new Vector3($x - 1, $y, $z - 1), new Vector3($x + 1, $y + 6, $z + 1), Block::get(Block::PLANKS));

				$newFloor = $random->nextBoolean ();
				$numberFloor = $random->nextBoundedInt(4 + ($newFloor ? 5 : 0));
				$possiblePathes = [
					$possiblePathes,
					($newFloor ?[
						self::DIR_XPLUS,
						self::DIR_XMIN,
						self::DIR_ZPLUS,
						self::DIR_ZMIN,
					] : [ ]),
				];
				for ($i = 7; $i > $newFloor; $i --) {
					$chooseNew =(int) $newFloor && $random->nextBoolean ();
					$choose = $random->nextBoundedInt(4);
					unset($possiblePathes[$chooseNew] [$choose]);
				}

				foreach ($possiblePathes[0] as $path) {
					switch ($path) {
						case self::DIR_XPLUS:
							$this->generateMineshaftPart($x + 2, $y, $z, self::DIR_XPLUS, $random);
							break;
						case self::DIR_XMIN:
							$this->generateMineshaftPart($x - 2, $y, $z, self::DIR_XMIN, $random);
							break;
						case self::DIR_ZPLUS:
							$this->generateMineshaftPart($x, $y, $z + 2, self::DIR_ZPLUS, $random);
							break;
						case self::DIR_ZMIN:
							$this->generateMineshaftPart($x, $y, $z - 2, self::DIR_ZMIN, $random);
							break;
					}
				}

				foreach ($possiblePathes[1] as $path) {
					switch ($path) {
						case self::DIR_XPLUS:
							$this->generateMineshaftPart($x + 2, $y + 4, $z, self::DIR_XPLUS, $random);
							break;
						case self::DIR_XMIN:
							$this->generateMineshaftPart($x - 2, $y + 4, $z, self::DIR_XMIN, $random);
							break;
						case self::DIR_ZPLUS:
							$this->generateMineshaftPart($x, $y + 4, $z + 2, self::DIR_ZPLUS, $random);
							break;
						case self::DIR_ZMIN:
							$this->generateMineshaftPart($x, $y + 4, $z - 2, self::DIR_ZMIN, $random);
							break;
					}
				}

				$webNum = $random->nextBoundedInt(5) + 2;
				for ($i = 0; $i < $webNum; $i ++) {
					$xx = $x + $random->nextBoundedInt(3) - 1;
					$yy = $y + $random->nextBoundedInt(6);
					$zz = $z + $random->nextBoundedInt(3) - 1;
					if ($level->getBlockIdAt($xx, $yy, $zz) == Block::AIR) {
						$level->setBlockIdAt($xx, $yy, $zz, Block::COBWEB);
					}
				}
				break;
			case self::TYPE_STAIRS:
				if ($y <= 5) {
					$this->generateMineshaftPart($x, $y, $z, $dir, $random);
					return;
				}
				for ($i = 0; $i < 4; $i ++) {
					switch ($i) {
						case self::DIR_XPLUS:
							BuildingUtils::fill($this->level, new Vector3($x + $i, $y - $i - 1, $z - 2), new Vector3($x + $i, $y - $i + 3, $z + 2), Block::get(Block::AIR));
							BuildingUtils::fillCallback(new Vector3($x + $i, $y - $i - 2, $z - 2), new Vector3($x + $i, $y - $i - 2, $z + 2), function ($v3, ChunkManager $level) {
								if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
									$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
								}
							}, $this->level);
							break;
						case self::DIR_XMIN:
							BuildingUtils::fill($this->level, new Vector3($x - $i, $y - $i - 1, $z - 2), new Vector3($x - $i, $y - $i + 3, $z + 2), Block::get(Block::AIR));
							BuildingUtils::fillCallback(new Vector3($x - $i, $y - $i - 2, $z - 2), new Vector3($x - $i, $y - $i - 2, $z + 2), function ($v3, ChunkManager $level) {
								if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
									$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
								}
							}, $this->level);
							break;
						case self::DIR_ZPLUS:
							BuildingUtils::fill($this->level, new Vector3($x - 2, $y - $i - 1, $z + $i), new Vector3($x + 2, $y - $i + 3, $z + $i), Block::get(Block::AIR));
							BuildingUtils::fillCallback(new Vector3($x - 2, $y - $i - 2, $z + $i), new Vector3($x + 2, $y - $i - 2, $z + $i), function ($v3, ChunkManager $level) {
								if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
									$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
								}
							}, $this->level);
							break;
						case self::DIR_ZMIN:
							BuildingUtils::fill($this->level, new Vector3($x - 2, $y - $i - 1, $z - $i), new Vector3($x + 2, $y - $i + 3, $z - $i), Block::get(Block::AIR));
							BuildingUtils::fillCallback(new Vector3($x - 2, $y - $i - 2, $z - $i), new Vector3($x + 2, $y - $i - 2, $z - $i), function ($v3, ChunkManager $level) {
								if ($level->getBlockIdAt($v3->x, $v3->y, $v3->z) == Block::AIR) {
									$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::PLANKS);
								}
							}, $this->level);
							break;
					}
				}

				switch ($i) {
					case self::DIR_XPLUS:
						$this->generateMineshaftPart($x + 4, $y - 4, $z, self::DIR_XPLUS, $random);
						break;
					case self::DIR_XMIN:
						$this->generateMineshaftPart($x - 4, $y - 4, $z, self::DIR_XMIN, $random);
						break;
					case self::DIR_ZPLUS:
						$this->generateMineshaftPart($x, $y - 4, $z + 4, self::DIR_ZPLUS, $random);
						break;
					case self::DIR_ZMIN:
						$this->generateMineshaftPart($x, $y - 4, $z - 4, self::DIR_ZMIN, $random);
						break;
				}
				break;
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::SAND) {
				break;
			}
		}
		return ++$y;
	}

}
