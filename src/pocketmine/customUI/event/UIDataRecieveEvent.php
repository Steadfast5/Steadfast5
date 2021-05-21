<?php

namespace pocketmine\customUI\event;

use pocketmine\network\protocol\DataPacket;
use pocketmine\Player;

class UIDataRecieveEvent extends UIEvent {

	public static $handlerList = null;

	public function __construct(DataPacket $packet, Player $player) {
		parent:__construct($packet, $player);
	}

	public function getData() {
		return json_decode($this->packet->formData);
	}

	public function getDataEncoded() {
		return $this->packet->formData;
	}

}
