<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\block\Leaves2;
use pocketmine\block\Wood2;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class ObjectSavannaTree extends TreeGenerator {

	const TRUNK = Wood2::ACACIA;
	const LEAF = Leaves2::ACACIA;

	public function generate(ChunkManager $level, Random $rand, Vector3 $position) {
		$i = $rand->nextBoundedInt(3) + $rand->nextBoundedInt(3) + 5;
		$flag = true;

		if ($position->getY() >= 1 && $position->getY() + $i + 1 <= 256) {
			for ($j = (int)$position->getY(); $j <= $position->getY() + 1 + $i; ++$j) {
				$k = 1;

				if ($j == $position->getY()) {
					$k = 0;
				}

				if ($j >= $position->getY() + 1 + $i - 2) {
					$k = 2;
				}

				$vector3 = new Vector3();

				for ($l = (int)$position->getX() - $k; $l <= $position->getX() + $k && $flag; ++$l) {
					for ($i1 = (int)$position->getZ() - $k; $i1 <= $position->getZ() + $k && $flag; ++$i1) {
						if ($j >= 0 && $j < 256) {

							$vector3->setComponents($l, $j, $i1);
							if (!$this->canGrowInto($level->getBlockIdAt((int)$vector3->x, (int)$vector3->y, (int)$vector3->z))) {
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
				$block = $level->getBlockIdAt($down->getFloorX(), $down->getFloorY(), $down->getFloorZ());
				$face = BlockFace::horizontalRandom($rand);
				if (($block == Block::GRASS || $block == Block::DIRT) && $position->getY() < 256 - $i - 1) {
					$this->setDirtAt($level, $position->getSide(Vector3::SIDE_DOWN));
					$k2 = $i - $rand->nextBoundedInt(4) - 1;
					$l2 = 3 - $rand->nextBoundedInt(3);
					$i3 = $position->getFloorX();
					$j1 = $position->getFloorZ();
					$k1 = 0;
					$faceX = $face->unitVector->x;
					$faceZ = $face->unitVector->z;
					for ($l1 = 0; $l1 < $i; ++$l1) {
						$i2 = $position->getFloorY() + $l1;
						if ($l1 >= $k2 && $l2 > 0) {
							$i3 += $faceX;
							$j1 += $faceZ;
							--$l2;
						}
						$blockpos = new Vector3($i3, $i2, $j1);
						$material = $level->getBlockIdAt($blockpos->getFloorX(), $blockpos->getFloorY(), $blockpos->getFloorZ());
						if ($material == Block::AIR || $material == Block::LEAVES) {
							$this->placeLogAt($level, $blockpos);
							$k1 = $i2;
						}
					}
					$blockpos2 = new Vector3($i3, $k1, $j1);
					for ($j3 = -3; $j3 <= 3; ++$j3) {
						for ($i4 = -3; $i4 <= 3; ++$i4) {
							if (abs($j3) != 3 || abs($i4) != 3) {
								$this->placeLeafAt($level, $blockpos2->add($j3, 0, $i4));
							}
						}
					}
					$blockpos2 = $blockpos2->getSide(Vector3::SIDE_UP);

					for ($k3 = -1; $k3 <= 1; ++$k3) {
						for ($j4 = -1; $j4 <= 1; ++$j4) {
							$this->placeLeafAt($level, $blockpos2->add($k3, 0, $j4));
						}
					}

					$this->placeLeafAt($level, $blockpos2->getSide(Vector3::SIDE_EAST, 2));
					$this->placeLeafAt($level, $blockpos2->getSide(Vector3::SIDE_WEST, 2));
					$this->placeLeafAt($level, $blockpos2->getSide(Vector3::SIDE_SOUTH, 2));
					$this->placeLeafAt($level, $blockpos2->getSide(Vector3::SIDE_NORTH, 2));
					$i3 = $position->getFloorX();
					$j1 = $position->getFloorZ();
					$face1X = mt_rand(-1, 1);
					$face1Z = mt_rand(-1, 1);

					if ($face1X != $faceX || $face1Z != $faceZ) {
						$l3 = $k2 - $rand->nextBoundedInt(2) - 1;
						$k4 = 1 + $rand->nextBoundedInt(3);
						$k1 = 0;

						for ($l4 = $l3; $l4 < $i && $k4 > 0; --$k4) {
							if ($l4 >= 1) {
								$j2 = $position->getFloorY() + $l4;
								$i3 += $face1X;
								$j1 += $face1Z;
								$blockpos1 = new Vector3($i3, $j2, $j1);
								$material1 = $level->getBlockIdAt($blockpos1->getFloorX(), $blockpos1->getFloorY(), $blockpos1->getFloorZ());

								if ($material1 == Block::AIR || $material1 == Block::LEAVES) {
									$this->placeLogAt($level, $blockpos1);
									$k1 = $j2;
								}
							}

							++$l4;
						}
						if ($k1 > 0) {
							$blockpos3 = new Vector3($i3, $k1, $j1);
							for ($i5 = -2; $i5 <= 2; ++$i5) {
								for ($k5 = -2; $k5 <= 2; ++$k5) {
									if (abs($i5) != 2 || abs($k5) != 2) {
										$this->placeLeafAt($level, $blockpos3->add($i5, 0, $k5));
									}
								}
							}
							$blockpos3 = $blockpos3->getSide(Vector3::SIDE_UP);
							for ($j5 = -1; $j5 <= 1; ++$j5) {
								for ($l5 = -1; $l5 <= 1; ++$l5) {
									$this->placeLeafAt($level, $blockpos3->add($j5, 0, $l5));
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

	private function placeLogAt(ChunkManager $worldIn, Vector3 $pos) {
		$this->setBlockAndNotifyAdequately($worldIn, $pos, Block::WOOD2, self::TRUNK);
	}

	private function placeLeafAt(ChunkManager $worldIn, Vector3 $pos) {
		$material = $worldIn->getBlockIdAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if ($material == Block::AIR || $material == Block::LEAVES) {
			$this->setBlockAndNotifyAdequately($worldIn, $pos, Block::LEAVES2, self::LEAF);
		}
	}

}
