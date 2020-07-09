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

use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\tile\Container;
use pocketmine\tile\ShulkerBox as ShulkerTile;
use pocketmine\tile\Tile;
use pocketmine\Player;

class ShulkerBox extends Solid {

	protected $id = self::SHULKER_BOX;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Shulker Box";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness(){
		return 5;
	}
/*
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = ShulkerTile::createNBT($this, $face, $item, $player);
		$items = $item->getNamedTag()->getTag(Container::TAG_ITEMS);
		if ($items !== null) {
			$nbt->setTag($items);
		}
		Tile::createTile(Tile::SHULKER_BOX, $this->level->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		($inv = $player->getInventory())->clear($inv->getHeldItemIndex());
		return true;
	}

	public function onBreak(Item $item, Player $player = null) {
		$t = $this->getLevel()->getTile($this);
		if ($t instanceof ShulkerTile) {
			$item = ItemFactory::get($this->id, $this->id != self::UNDYED_SHULKER_BOX ? $this->meta : 0, 1);
			$itemNBT = clone $item->getNamedTag();
			$itemNBT->setTag($t->getCleanedNBT()->getTag(Container::TAG_ITEMS));
			$item->setNamedTag($itemNBT);
			$this->getLevel()->dropItem($this->add(0.5, 0.5, 0.5), $item);
			$t->getInventory()->clearAll();
		}
		$this->getLevel()->setBlock($this, Block::get(Block::AIR), true, true);
		return true;
	}

	public function onActivate(Item $item, Player $player = null) {
		if ($player instanceof Player) {
			$t = $this->getLevel()->getTile($this);
			if (!($t instanceof ShulkerTile)) {
				$t = Tile::createTile(Tile::SHULKER_BOX, $this->level->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}
			$player->addWindow($t->getInventory());
		}
		return true;
	}
*/
	public function getDrops(Item $item){
		return [
			
		];
	}
}
