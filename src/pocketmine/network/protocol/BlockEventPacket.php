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
		$this->reset($playerProtocol);
		$this->putVarInt($this->eventType);
		$this->putVarInt($this->eventData);
		$this->putLFloat($this->x);
		$this->putLFloat($this->y);
		$this->putLFloat($this->z);
	}

	public function decode($playerProtocol) {
		$this->getHeader($playerProtocol);
		$this->eventType = $this->getVarInt();
		$this->eventData = $this->getVarInt();
		$this->x = $this->getLFloat();
		$this->y = $this->getLFloat();
		$this->z = $this->getLFloat();
	}

}
