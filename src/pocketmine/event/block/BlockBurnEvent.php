<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;

class BlockBurnEvent extends BlockEvent implements Cancellable {

	private $causingBlock;

	public function __construct(Block $block, Block $causingBlock) {
		parent::__construct($block);
		$this->causingBlock = $causingBlock;
	}

	public function getCausingBlock() {
		return $this->causingBlock;
	}

}
