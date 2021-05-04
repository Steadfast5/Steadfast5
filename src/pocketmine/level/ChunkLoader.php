<?php

namespace pocketmine\level;

use pocketmine\block\Block;
use pocketmine\level\format\Chunk;
use pocketmine\math\Vector3;

interface ChunkLoader {

	public function getLoaderId();

	public function isLoaderActive();

	public function getPosition();

	public function getX();

	public function getZ();

	public function getLevel();

	public function onChunkChanged(Chunk $chunk);

	public function onChunkLoaded(Chunk $chunk);

	public function onChunkUnloaded(Chunk $chunk);

	public function onChunkPopulated(Chunk $chunk);

	public function onBlockChanged(Vector3 $block);

}
