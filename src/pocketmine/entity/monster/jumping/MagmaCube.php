<?php

namespace pocketmine\entity\monster\jumping;

use pocketmine\entity\monster\JumpingMonster;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\Compound;

class MagmaCube extends JumpingMonster {

	const NETWORK_ID = 42;

	public $width = 0.51;
	public $height = 0.51;

	/**
	 * 0 = tiny
	 * 1 = small
	 * 2 = big
	 */
	private $cubeSize = -1;

	public function __construct(Level $level) {
		if ($this->cubeSize === -1) {
			$this->cubeSize = self::getRandomCubeSize();
		}
		parent::__construct($level);
	}

	public function initEntity() {
		parent::initEntity();
		$this->speed = 0.8;

		$this->fireProof = true;
		$this->setDamage([0, 3, 4, 6]);
	}

	public function getName() {
		return "MagmaCube";
	}

	public static function getRandomCubeSize() {
		$size = mt_rand(1, 3);
		$size !== 3 ?: $size = 4;
		return $size;
	}

	public function attackEntity(Entity $player) {
		if ($this->attackDelay > 10 && $this->distanceSquared($player) < 1) {
			$this->attackDelay = 0;
			if ($this->cubeSize === 0) {
				$damage = 3;
			} elseif ($this->cubeSize === 1) {
				$damage = 4;
			} elseif ($this->cubeSize === 2) {
				$damage = 6;
			} else {
				$damage = null;
			}
			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
			$player->attack($ev->getFinalDamage, $ev);
		}
	}

	public function getDrops() {
		$drops = [];
		switch (mt_rand(0, 1)) {
			case 0:
				$drops[] = Item::get(Item::NETHERRACK, 0, 1);
				break;
			case 1:
				$drops[] = Item::get(Item::MAGMA_CREAM, 0, 1);
				break;
		}
		return $drops;
	}

}
