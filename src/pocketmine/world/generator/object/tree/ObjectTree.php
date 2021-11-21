<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

abstract class ObjectTree {

	public static function growTree(ChunkManager $level, int $x, int $y, int $z, Random $random, int $type = 0) {
		switch ($type) {
			case Sapling::SPRUCE:
				$tree = new ObjectSpruceTree();
				break;
			case Sapling::BIRCH:
				$tree = new ObjectBirchTree();
				break;
			case Sapling::JUNGLE:
				$tree = new ObjectJungleTree();
				break;
			case 8:
				$tree = new ObjectTallBirchTree();
				break;
			case Sapling::OAK:
			default:
				$tree = new ObjectOakTree();
				break;
		}

		if ($tree->canPlaceObject($level, $x, $y, $z, $random)) {
			$tree->placeObject($level, $x, $y, $z, $random);
		}
	}

	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$radiusToCheck = 0;
		for ($yy = 0; $yy < $this->getTreeHeight() + 3; ++$yy) {
			if ($yy == 1 || $yy == $this->getTreeHeight()) {
				++$radiusToCheck;
			}
			for ($xx = -$radiusToCheck; $xx < ($radiusToCheck + 1); ++$xx) {
				for ($zz = -$radiusToCheck; $zz < ($radiusToCheck + 1); ++$zz) {
					if (!$this->overridable($level->getBlockIdAt($x + $xx, $y + $yy, $z + $zz))) {
						return false;
					}
				}
			}
		}

		return true;
	}

	public function getTreeHeight() {
		return 7;
	}

	protected function overridable(int $id) {
		switch ($id) {
			case Block::AIR:
			case Block::SAPLING:
			case Block::LOG:
			case Block::LEAVES:
			case Block::SNOW_LAYER:
			case Block::LOG2:
			case Block::LEAVES2:
				return true;
			default:
				return false;
		}
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->placeTrunk($level, $x, $y, $z, $random, $this->getTreeHeight() - 1);
		for ($yy = $y - 3 + $this->getTreeHeight(); $yy <= $y + $this->getTreeHeight(); ++$yy) {
			$yOff = $yy - ($y + $this->getTreeHeight());
			$mid = (int)(1 - $yOff / 2);
			for ($xx = $x - $mid; $xx <= $x + $mid; ++$xx) {
				$xOff = abs($xx - $x);
				for ($zz = $z - $mid; $zz <= $z + $mid; ++$zz) {
					$zOff = abs($zz - $z);
					if ($xOff == $mid && $zOff == $mid && ($yOff == 0 || $random->nextBoundedInt(2) == 0)) {
						continue;
					}
					if (!Block::get($level->getBlockIdAt($xx, $yy, $zz))->isSolid()) {
						$level->setBlockIdAt($xx, $yy, $zz, $this->getLeafBlock());
						$level->setBlockDataAt($xx, $yy, $zz, $this->getType());
					}
				}
			}
		}
	}

	protected function placeTrunk(ChunkManager $level, int $x, int $y, int $z, Random $random, int $trunkHeight) {
		$level->setBlockIdAt($x, $y - 1, $z, Block::DIRT);

		for ($yy = 0; $yy < $trunkHeight; ++$yy) {
			$blockId = $level->getBlockIdAt($x, $y + $yy, $z);
			if ($this->overridable($blockId)) {
				$level->setBlockIdAt($x, $y + $yy, $z, $this->getTrunkBlock());
				$level->setBlockDataAt($x, $y + $yy, $z, $this->getType());
			}
		}
	}

	public function getTrunkBlock() {
		return Block::LOG;
	}

	public function getType() {
		return 0;
	}

	public function getLeafBlock() {
		return Block::LEAVES;
	}

}
