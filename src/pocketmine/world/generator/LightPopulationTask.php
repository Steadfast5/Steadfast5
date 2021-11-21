<?php

namespace pocketmine\world\generator;

use pocketmine\world\format\FullChunk;
use pocketmine\world\World;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class LightPopulationTask extends AsyncTask {

	public $worldId;
	public $chunk;
	public $chunkClass;

	public function __construct(World $world, FullChunk $chunk) {
		$this->worldId = $world->getId();
		$this->chunk = $chunk->toFastBinary();
		$this->chunkClass = get_class($chunk);
	}

	public function onRun() {
		/** @var FullChunk $chunk */
		$chunk = $this->chunkClass;
		$chunk = $chunk::fromFastBinary($this->chunk);
		if ($chunk === null) {
			//TODO: error
			return;
		}

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = $chunk->toFastBinary();
	}

	public function onCompletion(Server $server) {
		$world = $server->getWorld($this->worldId);
		if ($world !== null) {
			/** @var FullChunk $chunk */
			$chunk = $this->chunkClass;
			$chunk = $chunk::fromFastBinary($this->chunk, $world->getProvider());
			if ($chunk === null) {
				//TODO: error
				return;
			}
			$world->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
