<?php

namespace GeneratorRemake;

use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AutoPopulator extends AsyncTask {

	public $state;
	public $levelId;
	public $chunk;
	public $chunkClass;
	public $chunk0;
	public $chunk1;
	public $chunk2;
	public $chunk3;
	public $chunk5;
	public $chunk6;
	public $chunk7;
	public $chunk8;

	public function __construct(Level $level, FullChunk $chunk) {
		$this->state = true;
		$this->levelId = $level->getId();
		$this->chunk = $chunk->toFastBinary();
		$this->chunkClass = get_class($chunk);
		for ($i = 0; $i < 9; ++$i) {
			if ($i === 4) {
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int)($i / 3);
			$ck = $level->getChunk($chunk->getX() + $xx, $chunk->getZ() + $zz, true);
			$this->{"chunk$i"} = $ck !== null ? $ck->toFastBinary() : null;
		}
	}

	public function onRun() {
		$manager = $this->getFromThreadStore("generation.level{$this->levelId}.manager");
		$generator = $this->getFromThreadStore("generation.level{$this->levelId}.generator");
		if ($manager === null or $generator === null) {
			$this->state = false;
			return;
		}
		$chunks = [];
		$chunkC = $this->chunkClass;
		$chunk = $chunkC::fromFastBinary($this->chunk);
		if ($chunk->isPopulated()) {
			return;
		}
		for ($i = 0; $i < 9; ++$i) {
			if ($i === 4) {
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int)($i / 3);
			$ck = $this->{"chunk$i"};
			if ($ck === null) {
				$chunks[$i] = $chunkC::getEmptyChunk($chunk->getX() + $xx, $chunk->getZ() + $zz);
			} else {
				$chunks[$i] = $chunkC::fromFastBinary($ck);
			}
		}
		if ($chunk === null) {

			return;
		}
		$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
		if (!$chunk->isGenerated()) {
			$generator->generateChunk($chunk->getX(), $chunk->getZ());
			$chunk->setGenerated();
		}
		foreach ($chunks as $c) {
			if ($c !== null) {
				$manager->setChunk($c->getX(), $c->getZ(), $c);
				if (!$c->isGenerated()) {
					$generator->generateChunk($c->getX(), $c->getZ());
					$c = $manager->getChunk($c->getX(), $c->getZ());
					$c->setGenerated();
				}
			}
		}
		$generator->populateChunk($chunk->getX(), $chunk->getZ());
		$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
		$chunk->recalculateHeightMap();
		$chunk->setPopulated();
		$this->chunk = $chunk->toFastBinary();
		$manager->setChunk($chunk->getX(), $chunk->getZ(), null);
		foreach ($chunks as $i => $c) {
			if ($c !== null) {
				$c = $chunks[$i] = $manager->getChunk($c->getX(), $c->getZ());
				if (!$c->hasChanged()) {
					$chunks[$i] = null;
				}
			} else {
				$chunks[$i] = null;
			}
		}
		$manager->cleanChunks();
		for ($i = 0; $i < 9; ++$i) {
			if ($i === 4) {
				continue;
			}
			$this->{"chunk$i"} = $chunks[$i] !== null ? $chunks[$i]->toFastBinary() : null;
		}
	}

	public function onCompletion(Server $server) {
		$level = $server->getLevel($this->levelId);
		if ($level !== null) {
			if ($this->state === false) {
				$level->registerGenerator();
				return;
			}
			$chunkC = $this->chunkClass;
			$chunk = $chunkC::fromFastBinary($this->chunk, $level->getProvider());
			if ($chunk === null) {
				return;
			}
			for ($i = 0; $i < 9; ++$i) {
				if ($i === 4) {
					continue;
				}
				$c = $this->{"chunk$i"};
				if ($c !== null) {
					$c = $chunkC::fromFastBinary($c, $level->getProvider());
					$level->generateChunkCallback($c->getX(), $c->getZ(), $c);
				}
			}
			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}

}
