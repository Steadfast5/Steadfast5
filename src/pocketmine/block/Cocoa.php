<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
 */

namespace pocketmine\block;

use pocketmine\event\block\BlockGrowEvent;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class Cocoa extends Transparent {

	protected $id = self::COCOA;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getHardness() {
		return 0.2;
	}

	public function getResistance() {
		return 15;
	}

	public function canBeActivated() {
		return true;
	}

	public function getName() {
		return "Cocoa";
	}

	public function getToolType() {
		return Tool::TYPE_AXE;
	}

	public function onActivate(Item $item, Player $player = null) {
		if ($item->getId() === Item::DYE && $item->getDamage() === 0x0f) {
			$block = clone $this;
			if ($block->meta > 7) {
				return false;
			}
			$block->meta += 4;
			Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($this, $block));
			if (!$ev->isCancelled()) {
				$this->getLevel()->setBlock($this, $ev->getNewState(), true, true);
			}
			$item->count--;
			return true;
		}
		return false;
	}

	public function onUpdate($type, $deep) {
		if ($type === Level::BLOCK_UPDATE_NORMAL) {
			$faces = [3, 4, 2, 5, 3, 4, 2, 5, 3, 4, 2, 5];
			if ($this->getSide($faces[$this->meta])->isTransparent() === true) {
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		} elseif ($type === BLOCK_UPDATE_RANDOM) {
			if (mt_rand(0, 45) === 1) {
				if ($this->meta <= 7) {
					$block = clone $this;
					$block->meta += 4;
					Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($this, $block));
					if (!$ev->isCancelled()) {
						$this->getLevel()->setBlock($this, $ev->getNewState(), true, true);
					} else {
						return Level::BLOCK_UPDATE_RANDOM;
					}
				}
			} else {
				return Level::BLOCK_UPDATE_RANDOM;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		if ($target->getId() === Block::WOOD && $target->getDamage() === 3) {
			if ($face !== 0 && $face !== 1) {
				$faces = [
					2 => 0,
					3 => 2,
					4 => 3,
					5 => 1,
				];
				$this->meta = $faces[$face];
				$this->getLevel()->setBlock($block, Block::get(Item::COCOA, $this->meta), true);
				return true;
			}
		}
		return false;
	}

	public function getDrops(Item $item) {
		return [
			[Item::COCOA, 0, 1],
		];
	}

}
