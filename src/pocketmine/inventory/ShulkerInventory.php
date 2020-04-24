<?php

namespace pocketmine\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\BlockEventPacket;
use pocketmine\network\protocol\LevelSoundEventPacket;
use pocketmine\tile\ShulkerBox;
use pocketmine\Player;

class ShulkerInventory extends ContainerInventory {

	protected $holder;

	public function __construct(ShulkerBox $tile) {
		parent::__construct($tile);
	}

	public function getName() {
		return "Shulker Box";
	}

	public function getHolder() {
		return $this->getHolder;
	}

	public function getSize() {
		return 27;
	}

	public function onOpen(Player $who) {
		parent::onOpen($who);
		if (count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level) {
			$this->broadcastBlockEventPacket($this->getHolder(), true);
			$level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKERBOX_OPEN);
		}
	}

	protected function broadcastBlockEventPacket($fx, $fy, $fz, $isOpen){
		$pk = new BlockEventPacket();
		$pk->x = (int)$fx;
		$pk->y = (int)$fy;
		$pk->z = (int)$fz;
		$pk->eventType = 1;
		$pk->eventData = $isOpen ? 1 : 0;
		$this->getHolder()->getLevel()->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
	}

	public function onClose(Player $who) {
		if (count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level) {
			$this->broadcastBlockEventPacket($this->getHolder(), false);
			$level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKERBOX_CLOSED);
		}
		parent::onClose($who);
	}
}
