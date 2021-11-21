<?php

namespace pocketmine\level\generator\object;

use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class BasicGenerator {

	public abstract function generate(ChunkManager $level, Random $rand, Vector3 $position);

	public function setDecorationDefaults() {

	}

	protected function setBlockAndNotifyAdequately(ChunkManager $level, Vector3 $pos, int $id = 0, int $meta = 0) {
		$this->setBlock($level, $pos, $id, $meta);
	}

	protected function setBlock(ChunkManager $level, Vector3 $v, int $id = 0, int $meta = 0) {
		$level->setBlockIdAt((int)$v->x, (int)$v->y, (int)$v->z, $id);
		$level->setBlockDataAt((int)$v->x, (int)$v->y, (int)$v->z, $meta);
	}

}
