<?php

namespace pocketmine\entity\monster\swimming;

use pocketmine\entity\monster\SwimmingMonster;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;

class Guardian extends SwimmingMonster {

	const NETWORK_ID = 49;

	public $width = 0.85;
	public $height = 0.85;

	public function initEntity() {
		parent::initEntity();

		$this->setMaxHealth(30);
	}

	public function getName() {
		return "Guardian";
	}

	public function getDrops() {
		return [
			Item::get(Item::RAW_FISH, 0, mt_rand(1, 2)),
			Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 1)),
		];
	}

}
