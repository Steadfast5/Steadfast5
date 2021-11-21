<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class ObjectBigSpruceTree extends ObjectSpruceTree {

	private $leafStartHeightMultiplier;
	private $baseLeafRadius;

	public function __construct(float $leafStartHeightMultiplier, int $baseLeafRadius) {
		$this->leafStartHeightMultiplier = $leafStartHeightMultiplier;
		$this->baseLeafRadius = $baseLeafRadius;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->treeHeight = $random->nextBoundedInt(15) + 20;

		$topSize = $this->treeHeight - (int)($this->treeHeight * $this->leafStartHeightMultiplier);
		$lRadius = $this->baseLeafRadius + $random->nextBoundedInt(2);

		$this->placeTrunk($level, $x, $y, $z, $random, $this->getTreeHeight() - $random->nextBoundedInt(3));

		$this->placeLeaves($level, $topSize, $lRadius, $x, $y, $z, $random);
	}

	protected function placeTrunk(ChunkManager $level, int $x, int $y, int $z, Random $random, int $trunkHeight) {
		$level->setBlockIdAt($x, $y - 1, $z, Block::DIRT);
		$radius = 2;

		for ($yy = 0; $yy < $trunkHeight; ++$yy) {
			for ($xx = 0; $xx < $radius; $xx++) {
				for ($zz = 0; $zz < $radius; $zz++) {
					$blockId = $level->getBlockIdAt($x, $y + $yy, $z);
					if ($this->overridable($blockId)) {
						$level->setBlockIdAt($x + $xx, $y + $yy, $z + $zz, $this->getTrunkBlock());
						$level->setBlockDataAt($x + $xx, $y + $yy, $z + $zz, $this->getType());
					}
				}
			}
		}
	}

}
