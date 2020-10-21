<?php

namespace pocketmine\entity\animal\flying;

use pocketmine\entity\Creature;
use pocketmine\entity\animal\FlyingAnimal;
use pocketmine\item\Item;
use pocketmine\Player;

class Parrot extends FlyingAnimal {

	const NETWORK_ID = 30;

	public $width = 0.5;
	public $height = 0.9;

	/**
	 * 0 = red
	 * 1 = blue
	 * 2 = green
	 * 3 = cyan
	 * 4 = silver
	 */
	private $birdType;
	private $foods = [
		Item::SEEDS,
		Item::BEETROOT_SEEDS,
		Item::MELON_SEEDS,
		Item::PUMPKIN_SEEDS,
		Item::WHEAT_SEEDS
	];

	public function getName() {
		return "Parrot";
	}

	public function initEntity() {
		parent::initEntity();
		$this->fireProof = false;
		$this->setMaxHealth(6);
		if (empty($this->birdType)) {
			$this->setBirdType($this->getRandomBirdType());
		}
	}

	public function targetOption(Creature $creature, float $distance) {
		if ($creature instanceof Player) {
			return $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == $foods && $distance <= 49;
		}
		return false;
	}

	public function getDrops() {
		return [
			Item::get(Item::FEATHER, 0, mt_rand(1, 2)),
		];
	}

	public function getRandomBirdType() {
		return mt_rand(0, 4);
	}

	public function setBirdType($type) {
		$this->birdType = $type;
		$this->setDataProperty(self::DATA_VARIANT, self::DATA_TYPE_INT, $type);
	}

	public function getBirdType() {
		return $this->birdType;
	}

}
