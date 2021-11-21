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

use pocketmine\world\format\FullChunk;

use pocketmine\world\World;
use pocketmine\world\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;



class GenerationTask extends AsyncTask {

	public $state;
	public $worldId;
	public $chunk;
	public $chunkClass;

	public function __construct(World $world, FullChunk $chunk){
		$this->state = true;
		$this->worldId = $world->getId();
		$this->chunk = $chunk->toFastBinary();
		$this->chunkClass = get_class($chunk);

	}

	public function onRun(){
			
	//	var_dump($this->getTaskId());
		/** @var SimpleChunkManager $manager */
		$manager = $this->getFromThreadStore("generation.world{$this->worldId}.manager");
		/** @var Generator $generator */
		$generator = $this->getFromThreadStore("generation.world{$this->worldId}.generator");
		if($manager === null or $generator === null){
			$this->state = false;
			return;
		}

		/** @var FullChunk $chunk */
		$chunk = $this->chunkClass;
		$chunk = $chunk::fromFastBinary($this->chunk);
		if($chunk === null){
			//TODO error
			return;
		}

		$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);

		$generator->generateChunk($chunk->getX(), $chunk->getZ());

		$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
		$chunk->setGenerated();
		$this->chunk = $chunk->toFastBinary();

		$manager->setChunk($chunk->getX(), $chunk->getZ(), null);
	}

	public function onCompletion(Server $server){
		$world = $server->getWorld($this->worldId);
		if($world !== null){
			if($this->state === false){
				$world->registerGenerator();
				return;
			}
			/** @var FullChunk $chunk */
			$chunk = $this->chunkClass;
			$chunk = $chunk::fromFastBinary($this->chunk, $world->getProvider());
			if($chunk === null){
				//TODO error
				return;
			}
			$world->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
