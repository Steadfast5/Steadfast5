<?php

namespace pocketmine\level\generator\normal\biome;

use pocketmine\block\Block;
use pocketmine\block\GoldOre;
use pocketmine\level\generator\normal\Normal;
use pocketmine\level\generator\normal\populator\Cactus;
use pocketmine\level\generator\normal\populator\DeadBush;
use pocketmine\level\generator\normal\populator\Ore;
use pocketmine\level\generator\normal\populator\OreType;
use pocketmine\level\generator\normal\populator\SugarCane;

class MesaPlainsBiome extends SandyBiome {

	public function __construct() {
		parent::__construct();

		$deadBush = new DeadBush();
		$deadBush->setBaseAmount(1);
		$deadBush->setRandomAmount(2);

		$cactus = new Cactus();
		$cactus->setBaseAmount(1);
		$cactus->setRandomAmount(2);

		$sugarCane = new SugarCane();
		$sugarCane->setRandomAmount(20);
		$sugarCane->setBaseAmount(3);

		$ores = new Ore();
		$ores->setOreTypes([
			new OreType(new GoldOre(), 2, 8, 0, 32),
		]);

		$this->addPopulator($deadBush);
		$this->addPopulator($cactus);
		$this->addPopulator($sugarCane);
		$this->addPopulator($ores);

		$this->setElevation(63, 81);

		$this->temperature = 2;
		$this->rainfall = 0.8;

		$this->setGroundCover([
			Block::get(Block::SAND, 1),
			Block::get(Block::SAND, 1),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 7),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 12),
			Block::get(Block::STAINED_HARDENED_CLAY, 12),
			Block::get(Block::STAINED_HARDENED_CLAY, 12),
			Block::get(Block::STAINED_HARDENED_CLAY, 14),
			Block::get(Block::STAINED_HARDENED_CLAY, 14),
			Block::get(Block::STAINED_HARDENED_CLAY, 14),
			Block::get(Block::STAINED_HARDENED_CLAY, 4),
			Block::get(Block::STAINED_HARDENED_CLAY, 7),
			Block::get(Block::STAINED_HARDENED_CLAY, 0),
			Block::get(Block::STAINED_HARDENED_CLAY, 7),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::HARDENED_CLAY, 0),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::STAINED_HARDENED_CLAY, 1),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
			Block::get(Block::RED_SANDSTONE, 0),
		]);
	}

	public function getName() {
		return "Mesa Plains";
	}

}
