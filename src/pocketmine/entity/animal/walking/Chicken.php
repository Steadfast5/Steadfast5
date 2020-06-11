<?php

namespace pocketmine\entity\animal\walking;

use pocketmine\entity\animal\WalkingAnimal;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Creature;

class Chicken extends WalkingAnimal {

	const NETWORK_ID = 10;

	public $length = 0.6;
	public $width = 0.6;
	public $height = 1.8;
	public $dropExp = [1, 3];

	public function getName(){
		return "Chicken";
	}

	public function initEntity(){
		parent::initEntity();

		$this->setMaxHealth(4);
	}

	public function spawnTo(Player $player) {
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Chicken::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function targetOption(Creature $creature, float $distance){
		if($creature instanceof Player){
			return $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == Item::SEEDS && $distance <= 49;
		}
		return false;
	}

	public function getDrops(){
		$drops = [];
		if ($this->lastDamageCause instanceof EntityDamageByEntityEvent && $this->lastDamageCause->getEntity() instanceof Player) {
			switch(mt_rand(0, 2)){
				case 0:
					$drops = Item::get(Item::RAW_CHICKEN, 0, 1);
					break;
				case 1:
					$drops = Item::get(Item::EGG, 0, 1);
					break;
				case 2:
					$drops =  Item::get(Item::FEATHER, 0, 2);
					break;
			}
		}
		return $drops;
	}

}
