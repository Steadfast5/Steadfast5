<?php

namespace GeneratorRemake\object;

use GeneratorRemake\utils\BuildingUtils;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Dungeons {

	const SMALL = 1;
	const NORMAL = 2;
	const MIDDLE = 3;
	const BIG = 4;

	public $overridable = [
		Block::AIR => true,
		17 => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
	];

	protected $height;

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		if (($type = mt_rand(1, 4)) == self::SMALL) {
			self::placeSmall($level, $x, $y, $z, $random);
		} elseif ($type == self::NORMAL) {
			self::placeNormal($level, $x, $y, $z, $random);
		} elseif ($type == self::MIDDLE) {
			self::placeMiddle($level, $x, $y, $z, $random);
		} else {
			self::placeBig($level, $x, $y, $z, $random);
		}
	}

	private static function placeSmall(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 3;
		$zDepth = 3;
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));
		for ($y = $pos1->y; $y >= $pos2->y; $y--) {
			for ($x = $pos1->x; $x >= $pos2->x; $x--) {
				for ($z = $pos1->z; $z >= $pos2->z; $z--) {
					$level->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}
		for ($x = $pos1->x; $x >= $pos2->x; $x--) {
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if (abs($x - $pos1->x) == 3 && abs($z - $pos1->z) == 3) {
					$level->setBlockIdAt($x, $y + 2, $z, Block::MONSTER_SPAWNER);
					self::prepareChest($x + 2, $y + 2, $z);
					self::prepareChest($x, $y + 2, $z + 2);
					self::prepareSpawner($x, $y + 2, $z);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
	}

	public static function prepareChest($x, $y, $z) {
		$file = fopen($f = getcwd() . DIRECTORY_SEPARATOR . "processingLoot.txt", "a+");
		if (!file_exists($f)) {
			fwrite($file, "{$x}:{$y}:{$z}:c" . PHP_EOL);
		} else {
			fwrite($file, PHP_EOL . "{$x}:{$y}:{$z}:c");
		}
		fclose($file);
	}

	public static function prepareSpawner(int $x, int $y, int $z) {
		$file = fopen($f = getcwd() . DIRECTORY_SEPARATOR . "processingLoot.txt", "a+");
		if (!file_exists($f)) {
			fwrite($file, "{$x}:{$y}:{$z}:s" . PHP_EOL);
		} else {
			fwrite($file, PHP_EOL . "{$x}:{$y}:{$z}:s");
		}
		fclose($file);
	}

	private static function placeNormal(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 3;
		$zDepth = 4;
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));
		for ($y = $pos1->y; $y >= $pos2->y; $y--) {
			for ($x = $pos1->x; $x >= $pos2->x; $x--) {
				for ($z = $pos1->z; $z >= $pos2->z; $z--) {
					$level->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}
		for ($x = $pos1->x; $x >= $pos2->x; $x--) {
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if (abs($x - $pos1->x) == 3 && abs($z - $pos1->z) == 3) {
					$level->setBlockIdAt($x, $y + 2, $z, Block::MONSTER_SPAWNER);
					self::prepareChest($x + 2, $y + 2, $z);
					self::prepareChest($x, $y + 2, $z + 3);
					self::prepareSpawner($x, $y + 2, $z);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
	}

	private static function placeMiddle(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 3;
		$zDepth = 5;
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));
		for ($y = $pos1->y; $y >= $pos2->y; $y--) {
			for ($x = $pos1->x; $x >= $pos2->x; $x--) {
				for ($z = $pos1->z; $z >= $pos2->z; $z--) {
					$level->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}
		for ($x = $pos1->x; $x >= $pos2->x; $x--) {
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if (abs($x - $pos1->x) == 3 && abs($z - $pos1->z) == 5) {
					$level->setBlockIdAt($x, $y + 2, $z, Block::MONSTER_SPAWNER);
					self::prepareChest($x + 2, $y + 2, $z);
					self::prepareChest($x, $y + 2, $z + 4);
					self::prepareSpawner($x, $y + 2, $z);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
	}

	private static function placeBig(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 5;
		$zDepth = 5;
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));
		for ($y = $pos1->y; $y >= $pos2->y; $y--) {
			for ($x = $pos1->x; $x >= $pos2->x; $x--) {
				for ($z = $pos1->z; $z >= $pos2->z; $z--) {
					$level->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}
		for ($x = $pos1->x; $x >= $pos2->x; $x--) {
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if (abs($x - $pos1->x) == 5 && abs($z - $pos1->z) == 5) {
					$level->setBlockIdAt($x, $y + 2, $z, Block::MONSTER_SPAWNER);
					self::prepareChest($x + 4, $y + 2, $z);
					self::prepareChest($x, $y + 2, $z + 4);
					self::prepareSpawner($x, $y + 2, $z);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
	}

}
