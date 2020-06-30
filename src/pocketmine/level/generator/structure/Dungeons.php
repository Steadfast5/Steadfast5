<?php

namespace pocketmine\level\generator\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\level\generator\utils\BuildingUtils;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Dungeons extends PopulatorObject {

	public $overridable = [
		Block::AIR => true,
		17 => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
	];

	protected $height;

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 3 + $random->nextBoundedInt(3);
		$zDepth = 3 + $random->nextBoundedInt(3);
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

		$level->setBlockIdAt($x, $y + 1, $z, Block::MOB_SPAWNER);
	}

}
