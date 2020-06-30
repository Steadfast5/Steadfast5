<?php

namespace pocketmine\level\sound;

use pocketmine\block\Block;
use pocketmine\network\protocol\LevelEventPacket;

class BlockPlaceSound extends GenericSound {

	protected $data;

	public function __construct(Block $b) {
		parent::__construct($b, LevelEventPacket::EVENT_SOUND_BLOCK_PLACE);
		$this->data = $b->getId();
	}

	public function encode() {
		$pk = new LevelEventPacket;
		$pk->evid = $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = $this->data;
		return $pk;
	}

}
