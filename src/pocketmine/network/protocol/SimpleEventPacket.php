<?php

namespace pocketmine\network\protocol;

class SimpleEventPacket extends PEPacket {

	const NETWORK_ID = Info::SIMPLE_EVENT_PACKET;
	const PACKET_NAME = "SIMPLE_EVENT_PACKET";

	const TYPE_ENABLE_COMMANDS = 1;
	const TYPE_DISABLE_COMMANDS = 2;

	public $eventType;

	protected function encode($playerProtocol) {
		$this->putLShort($this->eventType);
	}

	protected function decode($playerProtocol) {
		$this->eventType = $this->getLShort();
	}

}
