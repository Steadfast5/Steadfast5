<?php

namespace pocketmine\network\protocol\v120;

class ShowProfilePacket extends PEPacket {

	const NETOWORK_ID = Info120::SHOW_PROFILE_PACKET;
	const PACKET_NAME = "SHOW_PROFILE_PACKET";

	public $xuid;

	public function encode($playerProtocol) {
		$this->putString($this->xuid);
	}

	public function decode($playerProtocol) {
		$this->xuid = $this->getString();
	}

}
