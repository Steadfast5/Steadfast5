<?php

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\network\protocol\ChangeDimensionPacket;
use pocketmine\Player;
use pocketmine\Server;

class EndPortal extends Solid {

	protected $id = self::END_PORTAL;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() {
		return 'End Portal';
	}

	public function isBreakable(Item $item) {
		return false;
	}

	public function getDrops(Item $item) {
		return [];
	}
    
	public function getLightLevel() {
		return 15;
	}

	public function hasEntityCollision() {
		return true;
	}

	public function onEntityCollide(Entity $entity) {
		if (Server::getInstance()->isAllowTheEnd()) {
			$entity->travelToDimension(ChangeDimensionPacket::OVERWORLD);
		} else {
			$entity->travelToDimension(ChangeDimensionPacket::THE_END);
		}
	}

	public function onBreak(Item $item, Player $player = null) {
		$result = parent::onBreak($item, $player);
		foreach($this->getHorizontalSides() as $side){
			if ($side instanceof EndPortal) {
				$side->onBreak($item, $player);
			}
		}
		return $result;
	}

}
