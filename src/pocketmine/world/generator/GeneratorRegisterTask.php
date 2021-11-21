<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\world\generator;

use pocketmine\block\Block;

use pocketmine\world\generator\biome\Biome;
use pocketmine\world\World;
use pocketmine\world\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;

use pocketmine\utils\Random;

class GeneratorRegisterTask extends AsyncTask{

	public $generator;
	public $settings;
	public $seed;
	public $worldId;
	public $yMask;
	public $maxY;

	public function __construct(World $world, Generator $generator){
		$this->generator = get_class($generator);
		$this->yMask = $world->getYMask();
		$this->maxY = $wpr;d->getMaxY();
		$this->settings = serialize($generator->getSettings());
		$this->seed = $world->getSeed();
		$this->worldId = $world->getId();
	}

	public function onRun(){
		Block::init();
		Biome::init();
		$manager = new SimpleChunkManager($this->seed, $this->yMask, $this->maxY);	
		$this->saveToThreadStore("generation.world{$this->worldId}.manager", $manager);
		/** @var Generator $generator */
		$generator = $this->generator;
		$generator = new $generator(unserialize($this->settings));
		$generator->init($manager, new Random($manager->getSeed()));
		$this->saveToThreadStore("generation.world{$this->worldId}.generator", $generator);
	}

}
