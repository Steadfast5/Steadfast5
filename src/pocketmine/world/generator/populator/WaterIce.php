<?php

namespace pocketmine\level\generator\populator;

use pocketmine\level\generator\biome\Biome;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class WaterIce extends Populator {

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		for ($x = 0; $x < 16; $x++) {
			for ($z = 0; z < 16; $z++) {
				//$biome = new Biome();
			}
		}
	}

}
