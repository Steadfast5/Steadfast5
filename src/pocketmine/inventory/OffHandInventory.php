<?php

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\network\protocol\v120\InventorySlotPacket;
use pocketmine\network\protocol\v120\Protocol120;
use pocketmine\network\protocol\MobEquipmentPacket;
use pocketmine\Player;

class OffHandInventory extends BaseInventory {

	public $holder;

	public function __construct(Player $holder) {
		parent::__construct([], 1);
		$this->holder = $holder;
	}

	public function getHolder() {
		return $this->holder;
	}

	public function setSize($size) {
		return 1;
	}

	public function getName() {
		return "OffHandInventory";
	}

	public function setItemInOffHand(Item $item) {
		$this->setItem(0, $item);
		$pk = new InventorySlotPacket();
		$pk->windowId = Protocol120::CONTAINER_ID_OFFHAND;
		$pk->inventorySlot = 0;
		$pk->item = $this->getItemInOffHand();
		$this->holder->getServer()->broadcastPacket($this->holder->getViewers(), $pk);
		$this->holder->sendDataPacket($pk);
	}

	public function getItemInOffHand() {
		return $this->getItem(0);
	}

}
