<?php

namespace pocketmine\item;

class EnderPearl extends Item {
	
	public function __construct($meta = 0, $count = 1) {
		parent::__construct(Item::ENDER_PEARL, $meta, $count, "Ender Pearl");
	}
	
	public function getMaxStackSize() : int {
		return 16;
	}
}
