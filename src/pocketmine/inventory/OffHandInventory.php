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

	public function getSize() {
		return 1;
	}

	public function getName() {
		return "OffHandInventory";
	}

	public function setItemInOffHand(Item $item) {
		$this->setItem(0, $item);

		$this->broadcastMobEquipmentPacket();

		$pk = new InventorySlotPacket();
		$pk->windowId = Protocol120::CONTAINER_ID_OFFHAND;
		$pk->inventorySlot = 0;
		$pk->item = $this->getItemInOffHand();
		$this->holder->getServer()->batchPackets($this->holder->getLevel()->getPlayers(), [$pk]);

		$this->getPlayer()->namedtag->setTag($item->nbtSerialize(-1, "OffHand"), true);
	}

	public function getItemInOffHand() {
		return $this->getItem(0);
	}

	public function broadcastMobEquipmentPacket() {
		$pk = new MobEquipmentPacket();
		$pk->windowId = $this->getPlayer()->getWindowId($this);
		$pk->item = $this->getItemInOffHand();
		$pk->eid = $this->getPlayer()->getId();
		$this->holder->getServer()->batchPackets($this->holder->getLevel()->getPlayers(), [$pk]);
	}

}
