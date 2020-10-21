<?php

namespace pocketmine\entity\monster;

use pocketmine\entity\JumpingEntity;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;

abstract class JumpingMonster extends JumpingEntity implements Monster {

	protected $attackDelay = 0;

	private $minDamage = [0, 0, 0, 0];
	private $maxDamage = [0, 0, 0, 0];

	public abstract function attackEntity(Entity $player);

	public function getDamage($difficulty = null) {
		return mt_rand($this->getMinDamage($difficulty), $this->getMaxDamage($difficulty));
	}

	public function getMinDamage($difficulty = null) {
		if ($difficulty === null || !is_numeric($difficulty) || $difficulty > 3 || $difficulty < 0) {
			$difficulty = Server::getInstance()->getDifficulty();
		}
		return $this->minDamage[$difficulty];
	}

	public function getMaxDamage($difficulty = null) {
		if ($difficulty === null || !is_numeric($difficulty) || $difficulty > 3 || $difficulty < 0) {
			$difficulty = Server::getInstance()->getDifficulty();
		}
		return $this->maxDamage[$difficulty];
	}

	public function setDamage($damage, $difficulty = null) {
		if (is_array($damage)) {
			for ($i = 0; $i < 4; $i++) {
				$this->minDamage[$i] = $damage[$i];
				$this->maxDamage[$i] = $damage[$i];
			}
			return;
		} elseif ($difficulty === null) {
			$difficulty = Server::getInstance()->getDifficulty();
		}

		if ($difficulty >= 1 && $difficulty <= 3) {
			$this->minDamage[$difficulty] = $damage[$difficulty];
			$this->maxDamage[$difficulty] = $damage[$difficulty];
		}
	}

	public function setMinDamage($damage, $difficulty = null) {
		if (is_array($damage)) {
			for ($i = 0; $i < 4; $i++) {
				$this->minDamage[$i] = min($damage[$i], $this->getMaxDamage($i));
			}
			return;
		} elseif ($difficulty === null) {
			$difficulty = Server::getInstance()->getDifficulty();
		}

		if ($difficulty >= 1 && $difficulty <= 3) {
			$this->minDamage[$difficulty] = min((float) $damage, $this->getMaxDamage($difficulty));
		}
	}

	public function setMaxDamage($damage, $difficulty = null) {
		if (is_array($damage)) {
			for ($i = 0; $i < 4; $i++) {
				$this->maxDamage[$i] = max((int) $damage[$i], $this->getMaxDamage($i));
			}
			return;
		} elseif ($difficulty === null) {
			$difficulty = Server::getInstance()->getDifficulty();
		}

		if ($difficulty >= 1 && $difficulty <= 3) {
			$this->maxDamage[$difficulty] = max((int) $damage, $this->getMaxDamage($difficulty));
		}
	}

	public function onUpdate($currentTick) {
		if ($this->server->getDifficulty() < 1) {
			$this->close()
			return false;
		}

		if (!$this->isAlive()) {
			if (++$this->deadTicks >= 23) {
				$this->close();
				return false;
			}
			return true;
		}

		$tickDiff = $currentTick - $this->lastUpdate;
		$this->lastUpdate = $currentTick;
		$this->entityBaseTick($tickDiff);

		$target = $this->updateMove();
		if ($this->isFriendly()) {
			if (!$target instanceof Player) {
				if ($target instanceof Entity) {
					if (!$target->isClosed()) {
						$this->attackEntity($target);
					}
				} elseif ($target instanceof Vector3 && (($this->x - $target->x) ** 2 + ($this->z - $target->z) ** 2) <= 1) {
					$this->moveTime = 0;
				}
			}
		} else {
			if ($target instanceof Entity) {
				if (!$target->isClosed()) {
					$this->attackEntity($target);
				}
			} elseif ($target instanceof Vector3 (($this->x - $target->x) ** 2 + ($this->z - $target->z) ** 2) <= 1) {
				$this->moveTime = 0;
			}
		}
		return true;
	}

	public function entityBaseTick($tickDiff = 1) {
		//Timings::$timerEntityBaseTick->startTiming();

		$hasUpdate = parent::entityBaseTick($tickDiff);

		if ($this->isClosed() || $this->getLevel() === null) {
			return $hasUpdate;
		}

		$this->attackDelay += $tickDiff;
		if (!$this->hasEffect(Effect::WATER_BREATHING) && $this->isUnderwater()) {
			$hasUpdate = true;
			$airTicks = $this->getDataProperty(self::DATA_AIR) - $tickDiff;
			if ($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_DROWNING, 2);
				$this->attack($ev->getFinalDamage, $ev);
			}
			$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $airTicks);
		} else {
			$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, 300);
		}

		//Timings::$timerEntityBaseTick->stopTiming();
		return $hasUpdate;
	}

}
