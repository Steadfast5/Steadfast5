<?php

namespace pocketmine\network\protocol;

class BlockEventPacket extends PEPacket {

	const NETWORK_ID = Info::BLOCK_EVENT_PACKET;
	const PACKET_NAME = "BLOCK_EVENT_PACKET";

	public $eventType;
	public $eventData;
	public $x;
	public $y;
	public $z;

	public function encode($playerProtocol) {
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putVarInt($this->eventType);
		$this->putVarInt($this->eventData);
	}

	public function decode($playerProtocol) {
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->eventType = $this->getVarInt();
		$this->eventData = $this->getVarInt();
	}

}
