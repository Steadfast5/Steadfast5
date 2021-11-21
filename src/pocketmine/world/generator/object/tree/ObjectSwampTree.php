<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class ObjectSwampTree extends TreeGenerator {

	private $metaWood = Wood::OAK;
	private $metaLeaves = Leaves::OAK;

	public function generate(ChunkManager $worldIn, Random $rand, Vector3 $vectorPosition) {
		$position = new Vector3($vectorPosition->getFloorX(), $vectorPosition->getFloorY(), $vectorPosition->getFloorZ());
		$i = $rand->nextBoundedInt(4) + 5;
		$flag = true;
		if ($position->getY() >= 1 && $position->getY() + $i + 1 <= 256) {
			for ($j = $position->getY(); $j <= $position->getY() + 1 + $i; ++$j) {
				$k = 1;
				if ($j == $position->getY()) {
					$k = 0;
				}
				if ($j >= $position->getY() + 1 + $i - 2) {
					$k = 3;
				}
				$pos2 = new Vector3();
				for ($l = $position->getX() - $k; $l <= $position->getX() + $k && $flag; ++$l) {
					for ($i1 = $position->getZ() - $k; $i1 <= $position->getZ() + $k && $flag; ++$i1) {
						if ($j >= 0 && $j < 256) {
							$pos2->setComponents($l, $j, $i1);
							if (!$this->canGrowInto($worldIn->getBlockIdAt($pos2->x, $pos2->y, $pos2->z))) {
								$flag = false;
							}
						} else {
							$flag = false;
						}
					}
				}
			}
			if (!$flag) {
				return false;
			} else {
				$down = $position->getSide(Vector3::SIDE_DOWN);
				$block = $worldIn->getBlockIdAt($down->x, $down->y, $down->z);
				if (($block == Block::GRASS || $block == Block::DIRT) && $position->getY() < 256 - $i - 1) {
					$this->setDirtAt($worldIn, $down);
					for ($k1 = $position->getY() - 3 + $i; $k1 <= $position->getY() + $i; ++$k1) {
						$j2 = $k1 - ($position->getY() + $i);
						$l2 = 2 - $j2 / 2;
						for ($j3 = $position->getX() - $l2; $j3 <= $position->getX() + $l2; ++$j3) {
							$k3 = $j3 - $position->getX();
							for ($i4 = $position->getZ() - $l2; $i4 <= $position->getZ() + $l2; ++$i4) {
								$j1 = $i4 - $position->getZ();
								if (abs($k3) != $l2 || abs($j1) != $l2 || $rand->nextBoundedInt(2) != 0 && $j2 != 0) {
									$blockpos = new Vector3($j3, $k1, $i4);
									$id = $worldIn->getBlockIdAt($blockpos->x, $blockpos->y, $blockpos->z);
									if ($id == Block::AIR || $id == Block::LEAVES || $id == Block::VINE) {
										$this->setBlockAndNotifyAdequately($worldIn, $blockpos, Wood::LEAVES, $this->metaLeaves);
									}
								}
							}
						}
					}
					for ($l1 = 0; $l1 < $i; ++$l1) {
						$up = $position->getSide(Vector3::SIDE_UP, $l1);
						$id = $worldIn->getBlockIdAt($up->x, $up->y, $up->z);
						if ($id == Block::AIR || $id == Block::LEAVES || $id == Block::WATER || $id == Block::STILL_WATER) {
							$this->setBlockAndNotifyAdequately($worldIn, $up, Wood::WOOD, $this->metaWood);
						}
					}
					for ($i2 = $position->getY() - 3 + $i; $i2 <= $position->getY() + $i; ++$i2) {
						$k2 = $i2 - ($position->getY() + $i);
						$i3 = 2 - $k2 / 2;
						$pos2 = new Vector3();
						for ($l3 = $position->getX() - $i3; $l3 <= $position->getX() + $i3; ++$l3) {
							for ($j4 = $position->getZ() - $i3; $j4 <= $position->getZ() + $i3; ++$j4) {
								$pos2->setComponents($l3, $i2, $j4);
								if ($worldIn->getBlockIdAt($pos2->x, $pos2->y, $pos2->z) == Block::LEAVES) {
									$blockpos2 = $pos2->getSide(Vector3::SIDE_WEST);
									$blockpos3 = $pos2->getSide(Vector3::SIDE_EAST);
									$blockpos4 = $pos2->getSide(Vector3::SIDE_NORTH);
									$blockpos1 = $pos2->getSide(Vector3::SIDE_SOUTH);

									if ($rand->nextBoundedInt(4) == 0 && $worldIn->getBlockIdAt($blockpos2->x, $blockpos2->y, $blockpos2->z) == Block::AIR) {
										$this->addHangingVine($worldIn, $blockpos2, 8);
									}

									if ($rand->nextBoundedInt(4) == 0 && $worldIn->getBlockIdAt($blockpos3->x, $blockpos3->y, $blockpos3->z) == Block::AIR) {
										$this->addHangingVine($worldIn, $blockpos3, 2);
									}

									if ($rand->nextBoundedInt(4) == 0 && $worldIn->getBlockIdAt($blockpos4->x, $blockpos4->y, $blockpos4->z) == Block::AIR) {
										$this->addHangingVine($worldIn, $blockpos4, 1);
									}

									if ($rand->nextBoundedInt(4) == 0 && $worldIn->getBlockIdAt($blockpos1->x, $blockpos1->y, $blockpos1->z) == Block::AIR) {
										$this->addHangingVine($worldIn, $blockpos1, 4);
									}
								}
							}
						}
					}
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	private function addHangingVine(ChunkManager $worldIn, Vector3 $pos, int $meta) {
		$this->addVine($worldIn, $pos, $meta);
		$i = 4;

		for ($pos = $pos->getSide(Vector3::SIDE_DOWN); $i > 0 && $worldIn->getBlockIdAt($pos->x, $pos->y, $pos->z) == Block::AIR; --$i) {
			$this->addVine($worldIn, $pos, $meta);
			$pos = $pos->getSide(Vector3::SIDE_DOWN);
		}
	}

	private function addVine(ChunkManager $worldIn, Vector3 $pos, int $meta) {
		$this->setBlockAndNotifyAdequately($worldIn, $pos, Block::VINE, $meta);
	}

}
