<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\level\Position;

class BlockTeleportEvent extends BlockEvent implements Cancellable {

	public static $handlerList = null;

	protected $oldPosition;
	protected $newPosition;

	public function __construct(Block $block, Position $oldPosition, Position $newPosition) {
		$this->block = $block;
		$this->oldPosition = $oldPosition;
		$this->newPosition = $newPosition;
	}

	public function getBlock() {
		return $this->block;
	}

	public function getOldPosition() {
		return $this->oldPosition;
	}

	public function getNewPosition() {
		return $this->newPosition;
	}

}
