<?php

namespace pocketmine\entity\animal\walking;

use pocketmine\entity\animal\WalkingAnimal;
use pocketmine\entity\Creature;
use pocketmine\entity\Rideable;
use pocketmine\item\Item;
use pocketmine\Player;

class Llama extends WalkingAnimal implements Rideable {

	const NETWORK_ID = 29;

	public $width = 0.9;
	public $height = 1.87;

	public function initEntity() {
		parent::initEntity();

		$this->setMaxHealth(20);
	}

	public function getName() {
		return "Llama";
	}

	public function targetOption(Creature $creature, float $distance) {
		if ($creature instanceof Player) {
			return $creature->spawned && $creature->isAlive() && !$creature->isClosed() && $creature->getInventory()->getItemInHand()->getId() === Item::APPLE && $distance <= 49;
		}
		return false;
	}

	public function getDrops() {
		return [
			Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
		];
	}

	public function getRidePosition() {
		return null;
	}

}
