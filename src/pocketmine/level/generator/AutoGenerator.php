<?php

namespace pocketmine\level\generator;

use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AutoGenerator extends AsyncTask {

	public $levelId;
	public $chunkClass;
	public $chunks = [];

	public function __construct(Level $level, FullChunk $chunk) {
		$this->levelId = $level->getId();
		$this->chunkClass = get_class($chunk);
	}

	public function add(FullChunk $chunk) {
		$this->chunks[count($this->chunks)] = $chunk->toFastBinary();
	}

	public function onRun() {
		$manager = $this->getFromThreadStore("generation.level{$this->levelId}.manager");
		$generator = $this->getFromThreadStore("generation.level{$this->levelId}.generator");
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
		$level = $server->getLevel($this->levelId);
		$level->registerGenerator();
		$chunkC = $this->chunkClass;
		foreach ($this->chunks as $i => $chunk) {
			$chunk = $chunkC::fromFastBinary($chunk);
			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
