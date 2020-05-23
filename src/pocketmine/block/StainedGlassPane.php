<?php

namespace pocketmine\block;

class StainedGlassPane extends GlassPane {

	protected $id = self::STAINED_GLASS_PANE;

	const WHITE = 0;
	const ORANGE = 1;
	const MAGENTA = 2;
	const LIGHT_BLUE = 3;
	const YELLOW = 4;
	const LIME = 5;
	const PINK = 6;
	const GRAY = 7;
	const LIGHT_GRAY = 8;
	const CYAN = 9;
	const PURPLE = 10;
	const BLUE = 11;
	const BROWN = 12;
	const GREEN = 13;
	const RED = 14;
	const BLACK = 15;

	public function __construct($meta = 0) {
		$this->setDamage($meta);
	}

	public function getName() {
		return $this->getColorNameByMeta($this->meta) . 'Stained Glass Pane';
	}

}
