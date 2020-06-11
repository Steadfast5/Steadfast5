<?php

namespace pocketmine\level\generator\object;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\utils\VectorIterator;

class BigJungleTree extends Tree {

	private $height;
	private $firstGroup;
	private $secoundGroup;

	public function __construct() {
		$this->height = mt_rand(30, 40);
		$this->firstGroup = (int)($this->height * 0.6);
		$this->secoundGroup = (int)($this->height * 0.8);
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
//		for($yy = $y; $yy <= $this->height; $yy++) {
//			if($yy == $this->firstGroup) {
//
//			}
//			for($xx = $x; $xx <= $x+1; $xx) {
//				for($zz = $z; $zz <= $z+1; $zz) {
//					$level->setBlockIdAt($x, $y, $z, Block::LOG);
//					$level->setBlockIdAt($x, $y, $z, Wood::JUNGLE);
//				}
//			}
//		}
	}

}
