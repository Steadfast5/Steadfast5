<?php

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class PumpkinPie extends Item {

	public static $food = ['food' => 8, 'saturation' => 4.8];

	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::PUMPKIN_PIE, 0, $count, "Pumpkin Pie");
	}

	public function food() : int {
		return 8;
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

		if ($human instanceof Player && $human->getGamemode() === 1) {
			return;
		}

		if ($this->count == 1) {
			$human->getInventory()->setItemInHand(Item::get(self::AIR));
		} else {
			--$this->count;

			$human->getInventory()->setItemInHand($this);
		}
	}

}
