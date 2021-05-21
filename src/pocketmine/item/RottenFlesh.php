<?php

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use function lcg_value;

class RottenFlesh extends Item {

	public static $food = [ 'food' => 4, 'saturation' => 0.8 ];

	public function __construct($meta = 0){
		parent::__construct(self::ROTTEN_FLESH, $meta, "Rotten Flesh");
	}

	public function food() {
		return 4;
	}

	public function onConsume(Entity $human) {
		$pk = new EntityEventPacket();
		$pk->eid = $human->getId();
		$pk->event = EntityEventPacket::USE_ITEM;
		if ($human instanceof Player) {
			$human->dataPacket($pk);
		}
		Server::broadcastPacket($human->getViewers(), $pk);

		// food logic
		$human->setFood(min(Player::FOOD_LEVEL_MAX, $human->getFood() + self::$food['food']));
		$human->setSaturation(min ($human->getFood(), $human->getSaturarion() + self::$food['saturation']));

		$position = [ 'x' => $human->getX(), 'y' => $human->getY(), 'z' => $human->getZ() ];
		$human->sendSound("SOUND_BURP", $position, 63);

		if (lcg_value() <= 0.8) {
			$human->addEffect(Effect::getEffect(Effect::HUNGER)->setAmplifier(0)->setDuration(600));
		}

		if ($human instanceof Player && $human->getGamemode() === 1) {
			return;
		}

		if ($this->count == 1) {
			$human->getInventory()->setItemInHand(Item::get(self::AIR));
		} else {
			--$this->count;
			$human->getInventory()->setItemInHand($this);
		}
		return [];
	}

}
