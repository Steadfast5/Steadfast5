<?php

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves2;
use pocketmine\block\Wood2;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class AcaciaTree extends Tree {

	public function __construct() {
		$this->trunkBlock = Block::WOOD2;
		$this->leafBlock = Block::LEAVES2;
		$this->leafType = Leaves2::ACACIA;
		$this->type = Wood2::ACACIA;
		$this->treeHeight = 8;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {

	}
	// TODO: rewrite

}
