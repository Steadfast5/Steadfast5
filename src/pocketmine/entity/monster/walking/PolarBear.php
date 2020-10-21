<?php

namespace pocketmine\entity\monster\walking;

use pocketmine\entity\monster\WalkingMonster;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\IntTag;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Creature;

class PolarBear extends WalkingMonster {

	const NETWORK_ID = 28;

	private $angry = 0;

	public $width = 1.3;
	public $height = 1.4;

	public function getSpeed() {
		return 0.3;
	}

	public function initEntity() {
		parent::initEntity();

		if (isset($this->namedtag->Angry)) {
			$this->angry = (int) $this->namedtag["Angry"];
		}

		$this->setMaxHealth(30);
	}

	public function saveNBT() {
		parent::saveNBT();
		$this->namedtag->Angry = new IntTag("Angry", $this->angry);
	}

	public function getName() {
		return "Polar Bear";
	}

	public function isAngry() {
		return $this->angry > 0;
	}

	public function setAngry(int $val) {
		$this->angry = $val;
	}

	public function attack($damage, EntityDamageEvent $source) {
		parent::attack($damage, $source);

		if (!$source->isCancelled()) {
			$this->setAngry(1000);
		}
	}

	public function targetOption(Creature $creature, float $distance) {
		return $this->isAngry() && parent::targetOption($creature, $distance);
	}

	public function attackEntity(Entity $player) {
		if ($this->attackDelay > 10 && $this->distanceSquared($player) < 1.6) {
			$this->attackDelay = 0;

			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
			$player->attack($ev->getFinalDamage(), $ev);
		}
	}

	public function getDrops() {
		$drops = [];
		if (mt_rand(1, 4) > 1) {
			$drops[] = Item::get(Item::RAW_FISH, 1, mt_rand(0, 2));
			return $drops;
		} else {
			$drops[] = Item::get(Item::RAW_SALMON, 1, mt_rand(0, 2));
		}
	}

}
