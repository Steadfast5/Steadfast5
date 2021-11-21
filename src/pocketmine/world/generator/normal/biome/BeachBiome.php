<?php

namespace pocketmine\level\generator\normal\biome;

use pocketmine\level\generator\normal\populator\Cactus;
use pocketmine\level\generator\normal\populator\DeadBush;

class BeachBiome extends SandyBiome {

	public function __construct() {
		parent::__construct();

		$this->removePopulator(Cactus::class);
		$this->removePopulator(DeadBush::class);
		
		$this->setElevation(62, 65);
	}

	public function getName() {
		return "Beach";
	}

} 
