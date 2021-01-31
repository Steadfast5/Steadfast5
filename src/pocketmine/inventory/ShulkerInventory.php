<?php

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\LevelSoundEventPacket;
use pocketmine\network\protocol\TileEventPacket;
use pocketmine\tile\ShulkerBox;
use pocketmine\Player;
use pocketmine\Server;

class ShulkerInventory extends ContainerInventory {
/*
	protected $holder;

	public function __construct(ShulkerBox $tile) {
		parent::__construct($tile, InventoryType::get(InventoryType::SHULKER_BOX));
	}

	public function getName() {
		return "Shulker Box";
	}

	public function getHolder() {
		return $this->holder;
	}

	public function getSize() {
		return 27;
	}

	public function onOpen(Player $who) {
		parent::onOpen($who);
		if (count($this->getViewers()) === 1 {
			$pk = new TileEventPacket();
			$pk->x = $this->holder->getX();
			$pk->y = $this->holder->getY();
			$pk->z = $this->holder->getZ();
			$pk->case1 = 1;
			$pk->case2 = 2;
			if (($level = $this->getHolder()->getLevel()) instanceof Level) {
				Server::broadcastPacket($level->getUsingChunk($this->holder->getX() >> 4, $this->holder->getZ() >> 4), $pk);
			}
		}
		$position = [ 'x' => $this->holder->x, 'y' => $this->holder->y, 'z' => $this->holder->z ];
		$who->sendSound(LevelSoundEventPacket::SOUND_SHULKERBOX_OPEN, $position);
	}

	public function onClose(Player $who) {
		if (count($this->getViewers()) === 1) {
			$pk = new TileEventPacket();
			$pk->x = $this->holder->getX();
			$pk->y = $this->holder->getY();
			$pk->z = $this->holder->getZ();
			$pk->case1 = 1;
			$pk->case2 = 0;
			if (($level = $this->getHolder()->getLevel()) instanceof Level) {
				Server::broadcastPacket($level->getUsingChunk($this->holder->getX() >> 4, $this->holder->getZ() >> 4), $pk);
			}
		}
		parent::onClose($who);
		$position = [ 'x' => $this->holder->x, 'y' => $this->holder->y, 'z' => $this->holder->z ];
 		$who->sendSound(LevelSoundEventPacket::SOUND_SHULKERBOX_CLOSED, $position);
	}

	public function getFirstItem(&$itemIndex) {
		foreach ($this->getContents() as $index => $item) {
			if ($item->getId() != Item::AIR && $item->getCount() >= 0) {
				$itemIndex = $index;
				return $item;
			}
		}
		return null;
	}

	public function setItem($index, Item $item, $needCheckComporator = true) {
		if (parent::setItem($index, $item)) {
			if ($needCheckComporator) {
				if (!is_null($this->holder->level)) {
					$isShouldUpdateBlock = $item->getId() != Item::AIR && !$item->equals($this->getItem($index));
					if ($isShouldUpdateBlock) {
						$this->holder->getBlock()->onUpdate(Level::BLOCK_UPDATE_WEAK, 0);
					}
					static $offsets = [
						[1, 0, 0],
						[-1, 0, 0],
						[0, 0, -1],
						[0, 0, 1],
					];
					$tmpVector = new Vector3(0, 0, 0);
					foreach ($offsets as $offset) {
						$tmpVector->setComponents($this->holder->x + $offset[0], $this->holder->y, $this->holder->z + $offset[2]);
						if ($this->holder->level->getBlockIdAt($tmpVector->x, $tmpVector->y, $tmpVector->z) == Block::REDSTONE_COMPARATOR_BLOCK) {
							$comparator = $this->holder->level->getBlock($tmpVector);
							$comparator->onUpdate(Level::BLOCK_UPDATE_NORMAL, 0);
						}
					}
				}
			}
			return true;
		}
		return false;
	}
*/
}
