<?php

namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\Player;

class TotemOfUndying extends Item {
	
	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::TOTEM_OF_UNDYING, 0, $count, "Totem of Undying");
	}

	public function getMaxStackSize() {
		return 1;
	}

	public function onEntityDamage(EntityDamageEvent $event) {
		$entity = $event->getEntity;
		if ($entity instanceof Player) {
			if ($entity->getInventory()->getItemInHand()->getId() == Item::get(self::TOTEM_OF_UNDYING)) {
				if ($event->getCause() === EntityDamageEvent::CAUSE_VOID || $event->getCause() === EntityDamageEvent::CAUSE_SUICIDE) {
					return false;
				}
				if ($event->getDamage() >= $entity->getHealth()) {
					$entity->getInventory()->removeItem(Item::get($entity->getInventory()->getItemInHand()->getId(), 0, 1));
					$sound = new LevelEventPacket;
					$sound->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
					$event->setCancelled();
					$entity->removeAllEffects();
					$entity->setHealth(1);
					$entity->addEffect(Effect::getEffect(Effect::FIRE_RESISTANCE)->setAmplifier(1)->setDuration(40));
					$entity->addEffect(Effect::getEffect(Effect::REGENERATION)->setAmplifier(1)->setDuration(45));
					$entity->addEffect(Effect::getEffect(Effect::ABSORPTION)->setAmplifier(1)->setDuration(5));
				}
			}
		}
	}

}
