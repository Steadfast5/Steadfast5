<?php

namespace pocketmine\entity\monster\swimming;

use pocketmine\entity\monster\SwimmingMonster;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;

class ElderGuardian extends SwimmingMonster {

	const NETWORK_ID = 50;

	public $width = 1.9975;
	public $height = 1.9975;

	public function initEntity() {
		parent::initEntity();

		$this->setMaxHealth(80);
	}

	public function getName() {
		return "ElderGuardian";
	}

	public function getDrops() {
		return [
			Item::get(Item::PRISMARINE_CRYSTAL, 0, mt_rand(0, 1)),
			Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 2)),
		];
	}

}
