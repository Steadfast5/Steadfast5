<?php

namespace pocketmine\tile;

use pocketmine\inventory\ShulkerInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\nbt\tag\Compound;
use pocketmine\tile\Container;
use pocketmine\tile\Nameable;
use pocketmine\tile\Spawnable;

class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable {

	protected $inventory;

	public function getName() {
		return "Shulker Box";
	}

	public function close() {
		if (!$this->isClosed()) {
			$this->inventory->removeAllViewers(true);
			$this->inventory = null;
			parent::close();
		}
	}

	public function getRealInventory() {
		return $this->inventory;
	}

	public function getInventory() {
		return $this->inventory;
	}

	public function writeSaveData(Compound $nbt) {
		$this->saveName($nbt);
		$this->saveItems($nbt);
	}

	public function readSaveData(Compound $nbt) {
		$this->loadName($nbt);
		$this->inventory = new ShulkerInventory($this);
		$this->loadItems($nbt);
	}

}


