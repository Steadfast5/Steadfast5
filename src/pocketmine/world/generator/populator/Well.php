<?php

namespace pocketmine\world\generator\populator;

use pocketmine\block\Block;
use pocketmine\world\generator\structure\Well;
use pocketmine\world\ChunkManager;
use pocketmine\world\World;
use pocketmine\utils\Random;

class Well extends Populator {

	protected $world;

	public function populate(ChunkManager $world, $chunkX, $chunkZ, Random $random) {
		$this->world = $world;
		if ($random->nextBoundedInt(1000) > 25) {
			return;
		}
		$well = new Well();
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$y = $this->getHighestWorkableBlock($x, $z) - 1;
		if ($well->canPlaceObject($world, $x, $y, $z, $random)) {
			$well->placeObject($world, $x, $y, $z, $random);
		}
	}

	protected function getHighestWorkableBlock($x, $z) {
		for ($y = World::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->world->getBlockIdAt($x, $y, $z);
			if ($b === Block::SAND) {
				break;
			}
		}
		return ++$y;
	}

}
