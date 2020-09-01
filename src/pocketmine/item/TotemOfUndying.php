<?php

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\Player;

class TotemOfUndying extends Item {
	
	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::TOTEM_OF_UNDYING, $meta, $count, "Totem of Undying");
	}

	public function getMaxStackSize() {
		return 1;
	}

}
