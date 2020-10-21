<?php

namespace pocketmine\entity\animal\flying;

use pocketmine\entity\animal\FlyingAnimal;
use pocketmine\entity\Creature;

class Bat extends FlyingAnimal {

	const NETWORK_ID = 19;

	public $width = 0.484;
	public $height = 0.5;

	public function getName() {
		return "Bat";
	}

	public function initEntity() {
		parent::initEntity();

		$this->setMaxHealth(6);
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function targetOption(Creature $creature, float $distance) {
		return false;
	}

	public function getDrops() {
		return [];
	}

}
