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

use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\Level;

class Ice extends Transparent{

	protected $id = self::ICE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Ice";
	}

	public function getHardness(){
		return 0.5;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}
	
	public function onBreak(Item $item, Player $player = null) {
		if ($this->getLevel->getDimension == Level::DIMENSION_NETHER) {
			return;
		}

		if (($player === null || $player->isSurvival()) && !$item->hasEnchantment(Enchantment::SILK_TOUCH)) {
			return $this->getLevel()->setBlock($this, Block::get(Block::WATER), true);
		}
		return parent::onBreak($item, $player);
	}
}
