<?php

namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class BrownMushroomBlock extends Solid {

	const BROWN = 14;

	protected $id = self::BROWN_MUSHROOM_BLOCK;
	
	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function canBeActivated() {
		return true;
	}

	public function getName() {
		return "Brown Mushroom Block";
	}

	public function getHardness() {
		return 0.2;
	}

	public function getResistance() {
		return 1;
	}

	public function getDrops(Item $item) {
		if ($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0) {
			return [
				[Item::BROWN_MUSHROOM_BLOCK, self::BROWN, 1],
			];
		} else {
			return [
				[Item::BROWN_MUSHROOM, 0, mt_rand(0, 2)],
			];
		}
	}

}
