<?php

namespace pocketmine\world\generator\structure;

use pocketmine\block\Block;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\PopulatorObject;
use pocketmine\world\generator\utils\BuildingUtils;
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

	public function placeObject(ChunkManager $world, $x, $y, $z, Random $random) {
		$xDepth = 3 + $random->nextBoundedInt(3);
		$zDepth = 3 + $random->nextBoundedInt(3);
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));

		for ($y = $pos1->y; $y >= $pos2->y; $y--) {
			for ($x = $pos1->x; $x >= $pos2->x; $x--) {
				for ($z = $pos1->z; $z >= $pos2->z; $z--) {
					$world->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}

		for ($x = $pos1->x; $x >= $pos2->x; $x--) {
			for ($z = $pos1->z; $z >= $pos2->z; $z--) {
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if ($random->nextBoolean()) {
					$world->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$world->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
		$world->setBlockIdAt($x, $y + 1, $z, Block::MOB_SPAWNER);
	}

}
