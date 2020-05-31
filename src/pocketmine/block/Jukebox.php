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
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\item\Record;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;
use pocketmine\tile\Jukebox;

class Jukebox extends Solid {

	protected $id = self::JUKEBOX;

	public function __construct($id, $name = null){
		parent::__construct($id, 0, $name, null);
	}

	public function getName(){
		return "Jukebox";
	}

	public function getHardness(){
		return 2;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function verifyTile(Item $item, Player $player) {
		if ($this->getLevel()->getTile($this) === null) {
			Tile::createTile("Jukebox", $this->getLevel(), Jukebox::createNBT($this, 0, $item, $player));
			return 1;
		}
		return 0;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$this->getLevel()->setBlock($this, $this, true, true);
		$this->verifyTile($item, $player);
		return true;
	}

	public function onActivate(Item $item, Player $player = null) {
		if (!$player instanceof Player) {
			return false;
		}
		$this->verifyTile($item, $player);
		$tile = $this->getLevel()->getTile($this);
		$tile->handleInteract($item, $player);
		return true;
	}

	public function onBreak(Item $item, Player $player = null) {
		$this->verifyTile($item, $player);
		$tile = $this->getLevel()->getTile($this);
		$tile->handleBreak($item, $player);
		return parent::onBreak($item, $player);
	}

	public function getDrops(Item $item){
		return [
			[Item::JUKEBOX, 0, 1]
		];
	}

}
