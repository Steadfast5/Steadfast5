<?php

namespace pocketmine\event\player;

use pocketmine\entity\Human;
use pocketmine\event\Cancellable;

class PlayerConsumeTotemEvent extends PlayerEvent implements Cancellable {

	public function __construct(Human $entity) {
		$this->player = $entity;
	}

}
