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


class PopulationTask extends AsyncTask {

	public $state;
	public $worldId;
	public $chunk;
	public $chunkClass;

	public $chunk0;
	public $chunk1;
	public $chunk2;
	public $chunk3;
	//center chunk
	public $chunk5;
	public $chunk6;
	public $chunk7;
	public $chunk8;

	public function __construct(World $world, FullChunk $chunk){
		$this->state = true;
		$this->worldId = $world->getId();
		$this->chunk = $chunk->toFastBinary();
		$this->chunkClass = get_class($chunk);

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int) ($i / 3);
			$ck = $world->getChunk($chunk->getX() + $xx, $chunk->getZ() + $zz, false);
			$this->{"chunk$i"} = $ck !== null ? $ck->toFastBinary() : null;
		}
	}

	public function onRun(){
		/** @var SimpleChunkManager $manager */
		$manager = $this->getFromThreadStore("generation.world{$this->worldId}.manager");
		/** @var Generator $generator */
		$generator = $this->getFromThreadStore("generation.world{$this->worldId}.generator");
		if($manager === null or $generator === null){
			$this->state = false;
			return;
		}

		/** @var FullChunk[] $chunks */
		$chunks = [];
		/** @var FullChunk $chunkC */
		$chunkC = $this->chunkClass;

		$chunk = $chunkC::fromFastBinary($this->chunk);

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int) ($i / 3);
			$ck = $this->{"chunk$i"};
			if($ck === null){
				$chunks[$i] = $chunkC::getEmptyChunk($chunk->getX() + $xx, $chunk->getZ() + $zz);
			}else{
				$chunks[$i] = $chunkC::fromFastBinary($ck);
			}
		}

		if($chunk === null){
			//TODO error
			return;
		}

		$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
		if(!$chunk->isGenerated()){
			$generator->generateChunk($chunk->getX(), $chunk->getZ());
			$chunk->setGenerated();
		}

		foreach($chunks as $c){
			if($c !== null){
				$manager->setChunk($c->getX(), $c->getZ(), $c);
				if(!$c->isGenerated()){
					$generator->generateChunk($c->getX(), $c->getZ());
					$c = $manager->getChunk($c->getX(), $c->getZ());
					$c->setGenerated();
				}
			}
		}

		$generator->populateChunk($chunk->getX(), $chunk->getZ());

		$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
		$chunk->recalculateHeightMap();
		//$chunk->populateSkyLight();
		$chunk->setLightPopulated();
		$chunk->setPopulated();
		$this->chunk = $chunk->toFastBinary();

		$manager->setChunk($chunk->getX(), $chunk->getZ(), null);

		foreach($chunks as $i => $c){
			if($c !== null){
				$c = $chunks[$i] = $manager->getChunk($c->getX(), $c->getZ());
				if(!$c->hasChanged()){
					$chunks[$i] = null;
				}
			}else{
				//This way non-changed chunks are not set
				$chunks[$i] = null;
			}
		}

		$manager->cleanChunks();

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}

			$this->{"chunk$i"} = $chunks[$i] !== null ? $chunks[$i]->toFastBinary() : null;
		}
	}

	public function onCompletion(Server $server){
		$world = $server->getWorld($this->worldId);
		if($world !== null){
			if($this->state === false){
				$world->registerGenerator();
				return;
			}

			/** @var FullChunk $chunkC */
			$chunkC = $this->chunkClass;

			$chunk = $chunkC::fromFastBinary($this->chunk, $world->getProvider());

			if($chunk === null){
				//TODO error
				return;
			}

			for($i = 0; $i < 9; ++$i){
				if($i === 4){
					continue;
				}
				$c = $this->{"chunk$i"};
				if($c !== null){
					$c = $chunkC::fromFastBinary($c, $world->getProvider());
					$world->generateChunkCallback($c->getX(), $c->getZ(), $c);
				}
			}

			$world->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
