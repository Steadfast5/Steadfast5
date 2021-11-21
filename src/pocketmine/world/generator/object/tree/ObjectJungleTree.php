<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class ObjectJungleTree extends ObjectTree {

	private $treeHeight = 8;

	public function getTrunkBlock() {
		return Block::LOG;
	}

	public function getLeafBlock() {
		return Leaves::JUNGLE;
	}

	public function getType() {
		return Wood::JUNGLE;
	}

	public function getTreeHeight() {
		return $this->treeHeight;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->treeHeight = $random->nextBoundedInt(6) + 4;
		parent::placeObject($level, $x, $y, $z, $random);
	}

}
