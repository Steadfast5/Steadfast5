<?php

namespace pocketmine\customUI\event;

use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\v120\ModalFormResponsePacket;
use pocketmine\Player;

abstract class UIEvent {

	public static $handlerList = null;

	/** @var DataPacket|ModalFormResponsePacket $packet */
	protected $packet;
	/** @var Player */
	protected $player;

	public function __construct(DataPacket $packet, Player $player) {
		$this->packet = $packet;
		$this->player = $player;
	}

	public function getPacket() {
		return $this->packet;
	}

	public function getPlayer() {
		return $this->player;
	}

	public function getID() {
		return $this->packet->formId;
	}

}
