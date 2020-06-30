<?php

namespace pocketmine\event\record;

use pocketmine\block\Block;
use pocketmine\event\block\JukeboxEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class RecordStopEvent extends JukeboxEvent {

	private $player;
	private $block;
	private $record;

	public function __construct(Block $block, Item $record, Player $player = null) {
		parent::__construct($block);
		$this->block = $block;
		$this->player = $player;
		$this->record = $record;
	}

	public function getBlock() {
		return $this->block;
	}

	public function getPlayer() {
		return $this->player;
	}

	public function getRecord() {
		return $this->record;
	}

}
