<?php

namespace pocketmine\world\generator;

use pocketmine\world\format\FullChunk;
use pocketmine\world\generator\Generator;
use pocketmine\world\World;
use pocketmine\world\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AutoGenerator extends AsyncTask {

	public $worldId;
	public $chunkClass;
	public $chunks = [];

	public function __construct(World $world, FullChunk $chunk) {
		$this->worldId = $world->getId();
		$this->chunkClass = get_class($chunk);
	}

	public function add(FullChunk $chunk) {
		$this->chunks[count($this->chunks)] = $chunk->toFastBinary();
	}

	public function onRun() {
		$manager = $this->getFromThreadStore("generation.world{$this->worldId}.manager");
		$generator = $this->getFromThreadStore("generation.world{$this->worldId}.generator");
		$chunkC = $this->chunkClass;
		for ($i = 0; $i < count($this->chunks); $i++) {
			$chunk = $chunkC::fromFastBinary($this->chunks[$i]);
			$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
			$generator->generateChunk($chunk->getX(), $chunk->getZ());
			$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
			$chunk->setGenerated();
			$this->chunks[$i] = $chunk->toFastBinary();
		}
	}

	public function onCompletion(Server $server) {
		$world = $server->getWorld($this->worldId);
		$world->registerGenerator();
		$chunkC = $this->chunkClass;
		foreach ($this->chunks as $i => $chunk) {
			$chunk = $chunkC::fromFastBinary($chunk);
			$world->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
