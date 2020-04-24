<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Prismarine extends Solid {

	protected $id = self::PRISMARINE;

	const NORMAL = 0;
	const DARK = 1;
	const BRICKS = 2;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getName() {
		static $names = [
			self::NORMAL => "Prismarine",
			self::DARK => "Dark Prismarine",
			self::BRICKS => "Prismarine Bricks"
		];
		return isset($names[$this->meta]) ? $names[$this->meta] : $names[0];
	}

	public function getToolType() {
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() {
		return 5;
	}

	public function getDrops(Item $item) {
		if ($item->isPickaxe()) {
			return [ [ $this->id, $this->meta, 1 ] ];
		}
		return [];
	}
}
