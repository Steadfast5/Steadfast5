<?php

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\level\generator\utils\VectorIterator;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class AcaciaTree2 extends Tree {

	public $overridable = [
		Block::AIR => true,
		Block::LEAVES => true,
		Block::SAPLING => true
	];

	protected $radiusIncrease = -1;

	private $random;
	private $trunkHeightMultiplier = 0.6;
	private $trunkHeight;
	private $leafAmount = 1;
	private $leafDistanceLimit = 6;
	private $widthScale = 1;
	private $branchSlope = 0.5;
	private $leavesHeight = 2;
	private $addLeavesVines = false;
	private $addLogVines = false;
	private $addCocoaPlants = false;
	private $totalHeight;
	private $baseHeight = 3;

	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		if (!parent::canPlaceObject($level, $x, $y, $z, $random) || $level->getBlockIdAt($x, $y, $z) == Block::WATER || $level->getBlockIdAt($x, $y, $z) == Block::STILL_WATER) {
			return false;
		}
		$base = new Vector3($x, $y, $z);
		$this->totalHeight = $this->baseHeight + $random->nextBoundedInt(12);
		$availableSpace = $this->getAvailableBlockSpace($level, $base, $base->add(0, $this->totalHeight - 1, 0));
		if ($availableSpace > $this->baseHeight || $availableSpace == -1) {
			if ($availableSpace != -1) {
				$this->totalHeight = $availableSpace;
			}
			return true;
		}
		return false;
	}

	private function getAvailableBlockSpace(ChunkManager $level, Vector3 $from, Vector3 $to) {
		$count = 0;
		$iter = new VectorIterator($level, $from, $to);
		while ($iter->valid()) {
			$iter->next();
			$pos = $iter->current();
			if (!isset($this->overridable[$level->getBlockIdAt($pos->x, $pos->y, $pos->z)])) {
				return $count;
			}
			$count++;
		}
		return -1;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->random = $random;
		$this->trunkHeight = (int)($this->totalHeight * $this->trunkHeightMultiplier);
		$leaves = $this->getLeafGroupPoints($level, $x, $y, $z);
		$trunk = new VectorIterator($level, new Vector3($x, $y, $z), new Vector3($x, $y + $this->trunkHeight, $z));
		while ($trunk->valid()) {
			$trunk->next();
			$pos = $trunk->current();
			$level->setBlockIdAt($pos->x, $pos->y, $pos->z, Block::LOG2);
			$level->setBlockDataAt($pos->x, $pos->y, $pos->z, 0);
		}
		$this->generateBranches($level, $x, $y, $z, $leaves);
	}

	private function getLeafGroupPoints(ChunkManager $level, $x, $y, $z) {
		$amount = $this->leafAmount * $this->totalHeight / 13;
		$groupsPerLayer = (int)(0.1 + $amount * $amount);
		if ($groupsPerLayer == 0) {
			$groupsPerLayer = 1;
		}
		$trunkTopY = $y + $this->trunkHeight;
		$groups = [];
		$groupY = $y + $this->totalHeight - $this->leafDistanceLimit;
		$groups[] = [new Vector3($x, $groupY, $z), $trunkTopY];
		($currentLiar1 = (int)($this->totalHeight - $this->leafDistanceLimit));
		($currentLiar2 = (int)($this->trunkHeight - $this->leafDistanceLimit));
		for ($currentLayer = (int)($this->totalHeight - $this->leafDistanceLimit); $currentLayer >= 0; $currentLayer--) {
			$layerSize = $this->getRoughLayerSize($currentLayer);
			if ($layerSize < 0) {
				$groupY--;
				continue;
			}
			for ($count = 0; $count < $groupsPerLayer; $count++) {
				$scale = $this->widthScale * $layerSize * ($this->random->nextFloat() + 0.328);
				$randomOffset = self::createRandomDirection($this->random)->multiply($scale);
				$groupX = (int)($randomOffset->getX() + $x + 0.5);
				$groupZ = (int)($randomOffset->getY() + $z + 0.5);
				$group = new Vector3($groupX, $groupY, $groupZ);
				if ($this->getAvailableBlockSpace($level, $group, $group->add(0, $this->leafDistanceLimit, 0)) != -1) {
					continue;
				}
				$xOff = (int)($x - $groupX);
				$zOff = (int)($z - $groupZ);
				$horizontalDistanceToTrunk = sqrt($xOff * $xOff + $zOff * $zOff);
				$verticalDistanceToTrunk = $horizontalDistanceToTrunk * $this->branchSlope;
				$yDiff = (int)($groupY - $verticalDistanceToTrunk);
				if ($yDiff > $trunkTopY) {
					$base = $trunkTopY;
				} else {
					$base = $yDiff;
				}
				if ($this->getAvailableBlockSpace($level, new Vector3($x, $base, $z), $group) == -1) {
					$groups[] = [$group, $base];
				}
			}
			$groupY--;
		}
		return $groups;
	}

	private function getRoughLayerSize(int $layer) {
		$halfHeight = $this->totalHeight / 2;
		if ($layer < ($this->totalHeight / 3)) {
			return -1;
		} elseif ($layer == $halfHeight) {
			return $halfHeight / 4;
		} elseif ($layer >= $this->totalHeight || $layer <= 0) {
			return 0;
		} else {
			return sqrt($halfHeight * $halfHeight - ($layer - $halfHeight) * ($layer - $halfHeight)) / 2;
		}
	}

	public static function createRandomDirection(Random $random) {
		return self::getDirection2D($random->nextFloat() * 2 * pi());
	}

	public static function getDirection2D($azimuth) {
		return new Vector2(cos($azimuth), sin($azimuth));
	}

	private function generateBranches(ChunkManager $level, int $x, int $y, int $z, array $groups) {
		foreach ($groups as $group) {
			$baseY = $group[1];
			if (($baseY - $y) >= ($this->totalHeight * 0.2)) {
				$base = new Vector3($x, $baseY, $z);
				$branch = new VectorIterator($level, $base, $group[0]);
				while ($branch->valid()) {
					$branch->next();
					$pos = $branch->current();
					$level->setBlockIdAt((int)$pos->x, (int)$pos->y, (int)$pos->z, Block::LOG2);
					$level->setBlockDataAt((int)$pos->x, (int)$pos->y, (int)$pos->z, 0);
//					$level->updateBlockLight((int) $pos->x, (int) $pos->y, (int) $pos->z);
				}
				$this->generateGroupLayer($level, $group[0]->x, $group[0]->y + 1, $group[0]->z, 0);
			}
		}
	}

	private function generateGroupLayer(ChunkManager $level, int $x, int $y, int $z, int $size) {
		(int)$i3 = $x;
		(int)$j1 = $z;
		(int)$k1 = $y;
		$blockpos2 = new Vector3($i3, $k1, $j1);
		for ((int)$j3 = -3; $j3 <= 3; ++$j3) {
			for ((int)$i4 = -3; $i4 <= 3; ++$i4) {
				if (abs($j3) != 3 || abs($i4) != 3) {
					$this->setLeavesBlock($level, $blockpos2->add($j3, 0, $i4));
				}
			}
		}
		$blockpos2 = $blockpos2->getSide(Vector3::SIDE_UP);
		for ((int)$k3 = -1; $k3 <= 1; ++$k3) {
			for ((int)$j4 = -1; $j4 <= 1; ++$j4) {
				$this->setLeavesBlock($level, $blockpos2->add($k3, 0, $j4));
			}
		}
		$this->setLeavesBlock($level, $blockpos2->getSide(Vector3::SIDE_EAST, 2));
		$this->setLeavesBlock($level, $blockpos2->getSide(Vector3::SIDE_WEST, 2));
		$this->setLeavesBlock($level, $blockpos2->getSide(Vector3::SIDE_SOUTH, 2));
		$this->setLeavesBlock($level, $blockpos2->getSide(Vector3::SIDE_NORTH, 2));

	}

	private function setLeavesBlock($level, Vector3 $pos) {
		if (isset($this->overridable[$level->getBlockIdAt($pos->x, $pos->y, $pos->z)])) {
			$level->setBlockIdAt($pos->x, $pos->y, $pos->z, Block::LEAVES2);
			$level->setBlockDataAt($pos->x, $pos->y, $pos->z, 0);
		}
	}

	private function getLeafGroupLayerSize(int $y) {
		if ($y >= 0 && $y < $this->leafDistanceLimit) {
			return (int)(($y != ($this->leafDistanceLimit - 1)) ? 3 : 2);
		}
		return -1;
	}

}
