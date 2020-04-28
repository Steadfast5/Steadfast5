<?php

namespace pocketmine\tile;

use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\ShulkerInventory;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\NBT;
use pocketmine\tile\Container;
use pocketmine\tile\Nameable;
use pocketmine\tile\Spawnable;

abstract class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable {

	protected $inventory = null;

	public function __construct(FullChunk $chunk, Compound $nbt) {
		parent::__construct($chunk, $nbt);
		$this->inventory = new ShulkerInventory($this);
		if (!isset($this->namedtag->Items) || !($this->namedtag->Items instanceof Enum)) {
			$this->namedtag->Items = new Enum("Items", []);
			$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}
		for ($i = 0; $i < $this->getSize(); ++$i) {
			$this->inventory->setItem($i, $this->getItem($i));
		}
	}

	public function getName() {
		return "Shulker Box";
	}

	public function close() {
		if ($this->closed === false) {
			foreach ($this->inventory->getViewers() as $viewer) {
				$viewer->removeWindow($this->inventory);
			}
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
