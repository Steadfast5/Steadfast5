<?php

namespace pocketmine\level\generator\normal\biome;

use pocketmine\block\Block;
use pocketmine\block\GoldOre;
use pocketmine\block\StainedClay;
use pocketmine\level\generator\normal\Normal;
use pocketmine\level\generator\normal\populator\Cactus;
use pocketmine\level\generator\normal\populator\DeadBush;
use pocketmine\level\generator\normal\populator\Ore;
use pocketmine\level\generator\normal\populator\OreType;
use pocketmine\level\generator\normal\populator\SugarCane;
use pocketmine\level\generator\normal\populator\Tree;

class MesaBiome extends SandyBiome {

    public function __construct() {
        parent::__construct();

        $cactus = new Cactus();
        $cactus->setBaseAmount(0);
        $cactus->setRandomAmount(5);

        $deadBush = new DeadBush();
        $cactus->setBaseAmount(2);
        $deadBush->setRandomAmount(10);

		$sugarCane = new SugarCane();
		$sugarCane->setRandomAmount(20);
		$sugarCane->setBaseAmount(3);

		$trees = new Tree();
		$sugarCane->setRandomAmount(2);
		$sugarCane->setBaseAmount(0);

		$ores = new Ore();
		$ores->setOreTypes([
			new OreType(new GoldOre(), 2, 8, 0, 32),
		]);

        $this->addPopulator($cactus);
        $this->addPopulator($deadBush);
		$this->addPopulator($sugarCane);

        $this->setElevation(63, 81);

        $this->temperature = 2.0;
        $this->rainfall = 0.8;
        $this->setGroundCover([
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_ORANGE),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_BLACK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_GRAY),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_WHITE),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_ORANGE),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_YELLOW),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_BLACK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_WHITE),
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
        return "Mesa";
    }

} 
