<?php

namespace pocketmine\customUI\event;

use pocketmine\network\protocol\DataPacket;
use pocketmine\Player;

class UICloseEvent extends UIEvent {

	public static $handlerList = null;

	public function __construct(DataPacket $packet, Player $player) {
		parent::__construct($packet, $player);
	}

}
