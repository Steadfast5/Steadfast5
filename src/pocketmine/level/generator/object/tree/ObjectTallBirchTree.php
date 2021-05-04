<?php

namespace pocketmine\level\generator\object\tree;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class ObjectTallBirchTree extends ObjectBirchTree {

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random) {
		$this->treeHeight = $random->nextBoundedInt(3) + 10;
		parent::placeObject($level, $x, $y, $z, $random);
    }

}
