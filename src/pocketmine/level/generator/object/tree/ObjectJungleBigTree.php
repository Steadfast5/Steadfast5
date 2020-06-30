<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class ObjectJungleBigTree extends HugeTreesGenerator {

	public function __construct(int $baseHeightIn, int $extraRandomHeight, Block $woodMetadata, Block $leavesMetadata) {
		parent::__construct($baseHeightIn, $extraRandomHeight, $woodMetadata, $leavesMetadata);
	}

	public function generate(ChunkManager $level, Random $rand, Vector3 $position) {
		$height = $this->getHeight($rand);

		if (!$this->ensureGrowable($level, $rand, $position, $height)) {
			return false;
		} else {
			$this->createCrown($level, $position->getSide(Vector3::SIDE_DOWN, $height), 2);

			for ($j = (int)$position->getY() + $height - 2 - $rand->nextBoundedInt(4); $j > $position->getY() + $height / 2; $j -= 2 + $rand->nextBoundedInt(4)) {
				$f = $rand->nextFloat() * ((float)M_PI * 2);
				$k = (int)($position->getX() + (0.5 + cos($f) * 4.0));
				$l = (int)($position->getZ() + (0.5 + sin($f) * 4.0));

				for ($i1 = 0; $i1 < 5; ++$i1) {
					$k = (int)($position->getX() + (1.5 + cos($f) * (float)$i1));
					$l = (int)($position->getZ() + (1.5 + sin($f) * (float)$i1));
					$this->setBlockAndNotifyAdequately($level, new Vector3($k, $j - 3 + $i1 / 2, $l), $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
				}

				$j2 = 1 + $rand->nextBoundedInt(2);

				for ($k1 = $j - $j2; $k1 <= $j; ++$k1) {
					$l1 = $k1 - $j;
					$this->growLeavesLayer($level, new Vector3($k, $k1, $l), 1 - $l1);
				}
			}

			for ($i2 = 0; $i2 < $height; ++$i2) {
				$blockpos = $position->getSide(Vector3::SIDE_UP, $i2);

				if ($this->canGrowInto($level->getBlockIdAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z))) {
					$this->setBlockAndNotifyAdequately($level, $blockpos, $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
					if ($i2 > 0) {
						$this->placeVine($level, $rand, $blockpos->getSide(Vector3::SIDE_WEST), 8);
						$this->placeVine($level, $rand, $blockpos->getSide(Vector3::SIDE_NORTH), 1);
					}
				}

//				$this->setBlockAndNotifyAdequately($level, $position->getSide(Vector3::SIDE_UP, $i2 + 1), $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
//
//				for ($x = 0; $x < 5; $x++) {
//				$level->setBlockIdAt($position->getX() + $x, $i2, $position->getZ(), $this->leavesMetadata->getId());
//				$level->setBlockDataAt($position->getX() + $x, $i2, $position->getZ(), $this->leavesMetadata->getDamage());
//				$this->setBlockAndNotifyAdequately($level, $position->getSide(Vector3::SIDE_UP, $i2+1), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
//				}

				if ($i2 < $height - 1) {
					$blockpos1 = $blockpos->getSide(Vector3::SIDE_EAST);
					if ($this->canGrowInto($level->getBlockIdAt((int)$blockpos1->x, (int)$blockpos1->y, (int)$blockpos1->z))) {
						$this->setBlockAndNotifyAdequately($level, $blockpos1, $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
						if ($i2 > 0) {
							$this->placeVine($level, $rand, $blockpos1->getSide(Vector3::SIDE_EAST), 2);
							$this->placeVine($level, $rand, $blockpos1->getSide(Vector3::SIDE_NORTH), 1);
						}
					}
					$blockpos2 = $blockpos->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_EAST);
					if ($this->canGrowInto($level->getBlockIdAt((int)$blockpos2->x, (int)$blockpos2->y, (int)$blockpos2->z))) {
						$this->setBlockAndNotifyAdequately($level, $blockpos2, $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
						if ($i2 > 0) {
							$this->placeVine($level, $rand, $blockpos2->getSide(Vector3::SIDE_EAST), 2);
							$this->placeVine($level, $rand, $blockpos2->getSide(Vector3::SIDE_SOUTH), 4);
						}
					}
					$blockpos3 = $blockpos->getSide(Vector3::SIDE_SOUTH);
					if ($this->canGrowInto($level->getBlockIdAt((int)$blockpos3->x, (int)$blockpos3->y, (int)$blockpos3->z))) {
						$this->setBlockAndNotifyAdequately($level, $blockpos3, $this->woodMetadata->getId(), $this->woodMetadata->getDamage());
						if ($i2 > 0) {
							$this->placeVine($level, $rand, $blockpos3->getSide(Vector3::SIDE_WEST), 8);
							$this->placeVine($level, $rand, $blockpos3->getSide(Vector3::SIDE_SOUTH), 4);
						}
					}
				}
			}

			for ($y = 0; $y < 3; $y++) {
				for ($z = 0; $z <= 5 - $y; $z++) {
					if (($z > 0 + $y) && ($z < 4 - $y)) {
						for ($x = -5 + $y; $x <= 4 - $y; $x++) {
							if ($level->getBlockIdAt($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z) == 0) {
								$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
							}
							if ($level->getBlockIdAt($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1) == 0) {
								$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
							}
						}
					} else {
						if ($z == 4 - $y) {
							for ($x = -4 + $y; $x <= 3 - $y; $x++) {
								if ($level->getBlockIdAt($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z) == 0) {
									$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
								}
								if ($level->getBlockIdAt($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1) == 0) {
									$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
								}
							}
						} else {
							for ($x = -6 + $z + $y; $x <= 5 - $z - $y; $x++) {
								if ($level->getBlockIdAt($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z) == 0) {
									$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() - $x, (int)$position->getY() + $height - 1 + $y, $position->getZ() - $z), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
								}
								if ($level->getBlockIdAt($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1) == 0) {
									$this->setBlockAndNotifyAdequately($level, new Vector3($position->getX() + $x + 1, (int)$position->getY() + $height - 1 + $y, $position->getZ() + $z + 1), $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
								}
							}
						}
					}
				}
			}
			return true;
		}
	}

	private function createCrown(ChunkManager $level, Vector3 $pos, int $i1) {
		for ($j = -2; $j <= 0; ++$j) {
			$this->growLeavesLayerStrict($level, $pos->getSide(Vector3::SIDE_UP, $j), $i1 + 1 - $j);
		}
	}

	private function placeVine(ChunkManager $level, Random $random, Vector3 $pos, int $meta) {
		if ($random->nextBoundedInt(3) > 0 && $level->getBlockIdAt((int)$pos->x, (int)$pos->y, (int)$pos->z) == 0) {
			$this->setBlockAndNotifyAdequately($level, $pos, Block::VINE, $meta);
		}
	}

}
