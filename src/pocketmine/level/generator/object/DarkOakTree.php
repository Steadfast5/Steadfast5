<?php

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\block\Leaves2;
use pocketmine\block\Wood2;

class DarkOakTree extends Tree {

	public function __construct() {
		$this->trunkBlock = Block::WOOD2;
		$this->leafBlock = Block::LEAVES2;
		$this->leafType = Leaves2::DARK_OAK;
		$this->type = Wood2::DARK_OAK;
		$this->treeHeight = 8;
	}

}
