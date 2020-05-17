<?php

namespace pocketmine\level\generator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\biome\Biome;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\level\format\FullChunk;

class VoidWorld extends Generator {

	/** @var ChunkManager */
	private $level;
	/** @var FullChunk */
	private $chunk;
	/** @var Random */
	private $random;
	private $options;
	/** @var FullChunk */
	private $emptyChunk = null;

	public function getSettings() {
		return [];
	}

	public function getName() {
		return "Void";
	}

	public function __construct(array $settings = []) {
		$this->options = $settings;
	}

	public function init(ChunkManager $level, Random $random) {
		$this->level = $level;
		$this->random = $random;
	}

	public function generateChunk($chunkX, $chunkZ) {
		if ($this->emptyChunk !== null) {
			//Use the cached empty chunk instead of generating a new one
			$this->chunk = clone $this->emptyChunk;
		} else {
			$this->chunk = clone $this->level->getChunk($chunkX, $chunkZ);
			$this->chunk->setGenerated();
			$c = Biome::getBiome(1)->getColor();
			$R = $c >> 16;
			$G = ($c >> 8) & 0xff;
			$B = $c & 0xff;

			for ($Z = 0; $Z < 16; ++$Z) {
				for ($X = 0; $X < 16; ++$X) {
					$this->chunk->setBiomeId($X, $Z, 1);
					$this->chunk->setBiomeColor($X, $Z, $R, $G, $B);
					for ($y = 0; $y < 128; ++$y) {
						$this->chunk->setBlockId($X, $y, $Z, Block::AIR);
					}
				}
			}
			$spawn = $this->getSpawn();
			if ($spawn->getX() >> 4 === $chunkX && $spawn->getZ() >> 4 === $chunkZ) {
				$this->chunk->setBlockId(0, 64, 0, Block::GRASS);
			} else {
				$this->emptyChunk = $this->chunk;
			}
		}
		$chunk = clone $this->chunk;
		$chunk->setX($chunkX);
		$chunk->setZ($chunkZ);
		$this->level->setChunk($chunkX, $chunkZ, $chunk);
	}

	public function populateChunk($chunkX, $chunkZ) {

	}

	public function getSpawn() {
		return new Vector3(128, 72, 128);
	}

}
