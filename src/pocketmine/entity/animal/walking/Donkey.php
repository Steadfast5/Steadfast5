<?php

namespace pocketmine\entity\animal\walking;

use pocketmine\entity\animal\WalkingAnimal;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Creature;

class Donkey extends WalkingAnimal {

	const NETWORK_ID = 24;

	public $width = 0.3;
	public $length = 0.9;
	public $height = 0;

	public function getName() {
		return "Donkey";
	}

	public function initEntity() {
		parent::initEntity();

		$this->setMaxHealth(30);
	}

	public function targetOption(Creature $creature, float $distance) {
		return false;
	}

	public function getDrops() {
		$drops = [
			Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
		];
		return $drops;
	}

}
