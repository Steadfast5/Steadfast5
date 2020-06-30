<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class ObjectBirchTree extends ObjectTree {

	protected $treeHeight = 7;

	public function getTrunkBlock() {
		return Block::LOG;
	}

	public function getLeafBlock() {
		return Block::LEAVES;
	}

	public function getType() {
		return Wood::BIRCH;
	}

	public function getTreeHeight() {
		return $this->treeHeight;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->treeHeight = $random->nextBoundedInt(2) + 5;
		parent::placeObject($level, $x, $y, $z, $random);
	}

}
