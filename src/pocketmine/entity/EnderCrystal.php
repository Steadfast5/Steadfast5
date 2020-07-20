<?php

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Explosion;
use pocketmine\level\generator\ender\Ender;

class EnderCrystal extends Entity {

	const NETWORK_ID = self::ENDER_CRYSTAL;

	public $height = 0.98;
	public $width = 0.98;

	public $gravity = 0;
	public $drag = 0;

	public function onUpdate($currentTick) {
		if ($this->level->getProvider()->getPath() === Ender::class) {
			if ($this->level->getBlock($this)->getId() !== Block::FIRE) {
				$this->level->setBlock($this, new Fire());
			}
		}

		return parent::onUpdate($currentTick);
	}

	public function attack($damage, EntityDamageEvent $source) {
		parent::attack($damage, $source);

		if (
			!$this->isFlaggedForDespawn() &&
			!$source->isCancelled() &&
			$source->getCause() !== EntityDamageEvent::CAUSE_FIRE &&
			$source->getCause() !== EntityDamageEvent::CAUSE_FIRE_TICK
		) {
			$this->flagForDespawn();

			$explosion = new Explosion($this, 6, $this);
			$explosion->explodeA();
			$explosion->explodeB();
		}
	}

	public function canBeCollidedWith() {
		return false;
	}

	public function setShowBase($value) {
		$this->setDataFlag(self::DATA_FLAG_SHOWBASE, $value);
	}

	public function showBase() {
		return $this->getDataFlag(self::DATA_FLAG_SHOWBASE);
	}

}
