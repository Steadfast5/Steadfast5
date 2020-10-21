<?php

namespace pocketmine\entity\monster\jumping;

use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\monster\JumpingMonster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\Server;

class Slime extends JumpingMonster {

	const NETWORK_ID = 37;

	/**
	 * 0 = tiny
	 * 1 = small
	 * 2 = big
	 */
	private $cubeSize = -1;

	public function __construct(Level $level) {
		if ($this->cubeSize === -1) {
			$this->cubeSize = self::getRandomSlimeSize();
		}

		$this->setMaxHealth(4);
		$this->width = 0.51;
		$this->height 0.51;
		parent::__construct($level);
	}

	public function initEntity() {
		parent::initEntity();
		$this->speed = 0.8;

		$this->setDamage([0, 2, 2, 3]);
	}

	public function getName() {
		return "Slime";
	}

	public function getRandomSlimeSize() {
		$size = mt_rand(1, 3);
		$size !== 3 ?: $size = 4;
		return $size;
	}

	public function attackEntity(Entity $player) {
		if ($this->attackDelay > 10 && $this->distanceSquared($player) < 2) {
			$this->attackDelay = 0;
			if (Server::getInstance()->getDifficulty === 1) { // easy
				if ($this->cubeSize === 0) { // tiny
					$damage = 0;
				} elseif ($this->cubeSize === 1) { // small
					$damage = 2;
				} elseif ($this->cubeSize === 2) { // big
					$damage = 3;
				} else {
					$damage = null;
				}
			} elseif (Server::getInstance()->getDifficulty === 2) { // normal
				if ($this->cubeSize === 0) { // tiny
					$damage = 0;
				} elseif ($this->cubeSize === 1) { // small
					$damage = 2;
				} elseif ($this->cubeSize === 2) { // big
					$damage = 4;
				} else {
					$damage = null;
				}
			} elseif (Server::getInstance()->getDifficulty === 3) { // hard
				if ($this->cubeSize === 0) { // tiny
					$damage = 0;
				} elseif ($this->cubeSize === 1) { // small
					$damage = 3;
				} elseif ($this->cubeSize === 2) { // big
					$damage = 6;
				} else {
					$damage = null;
				}
			} else {
				$damage = null;
			}
			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
			$ev->attack($ev->getFinalDamage, $ev);
		}
	}

	public function targetOption(Creature $creature, $distance) {
		if ($creature instanceof Player) {
			return $creature->isAlive() && $distance <= 25;
		}
		return false;
	}

	public function getDrops() {
		if ($this->cubeSize === 0) {
			return [
				Item::get(Item::SLIMEBALL, 0, mt_rand(0, 2)),
			];
		} else {
			return [];
		}
	}

}
