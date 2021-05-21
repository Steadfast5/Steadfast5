<?php

namespace GeneratorRemake\object\structure;

use GeneratorRemake\object\Object;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;

class Village extends Object {

	private $level;

	public function placeObject(ChunkManager $level, int $chunkX, int $chunkZ) {
		$x = $chunkX << 4;
		$z = $chunkZ << 4;
		$this->level = $level;
	}

	public function generateRoad(int $length_x, int $length_z, int $real_x, int $real_z) {
		$y = $this->getHighestWorkableBlock($real_x, $real_z);
		for ($x = 0; $x < $length_x; $x++) {
			for ($z = 0; $z < $length_z; $z++) {
				while ($this->level->getBlockIdAt($real_x + $x, $y, $real_z + $z) != Block::AIR) {

				}
			}
		}
	}

	private function getHighestWorkableBlock($x, $z){
		for ($y = 127; $y >= 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR && $b !== Block::LEAVES && $b !== Block::LEAVES2 && $b !== Block::SNOW_LAYER) {
				break;
			}
		}
		return $y === 0 ? -1 : ++$y;
	}

}
