<?php

namespace pocketmine\block;

class CommandBlock extends Solid {

	protected $id = self::COMMAND_BLOCK;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function canBeActivated() {
		return true;
	}

	public function getName() {
		return "Command Block";
	}

	public function getHardness() {
		return -1;
	}
}
