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

use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\Server;

class Sponge extends Solid {

	protected $id = self::SPONGE;
	protected $absorbRange = 6;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 0.6;
	}

	public function absorbWater() {
		$range = $this->absorbRange / 2;
		for ($xx = -$range; $xx <= $range; $xx++) {
			for ($yy = -$range; $yy <= $range; $yy++) {
				for ($zz = -$range; $zz <= $range; $zz++) {
					$block = $this->getLevel()->getBlock(new Vector3($this->x + $xx, $this->y + $yy, $this->z + $zz));
					if ($block->getId() === Block::WATER) {
						$this->getLevel()->setBlock($block, Block::get(Block::AIR), true, true);
					}
					if ($block->getIds() === Block::STILL_WATER) {
						$this->getLevel()->setBlock($block, Block::get(Block::AIR), true, true);
					}
				}
			}
		}
	}

	public function onUpdate($type) {
		if ($this->meta == 0) {
			if ($type === Level::BLOCK_UPDATE_NORMAL) {
				$blockAbove = $this->getSide(Vector3::SIDE_UP)->getId();
				$blockBeneath = $this->getSide(Vector3::SIDE_DOWN)->getId();
				$blockNorth = $this->getSide(Vector3::SIDE_NORTH)->getId();
				$blockSouth = $this->getSide(Vector3::SIDE_SOUTH)->getId();
				$blockEast = $this->getSide(Vector3::SIDE_EAST)->getId();
				$blockWest = $this->getSide(Vector3::SIDE_WEST)->getId();

				if (
					$blockAbove === Block::WATER ||
					$blockBeneath === Block::WATER ||
					$blockNorth === Block::WATER ||
					$blockSouth === Block::WATER ||
					$blockEast === Block::WATER ||
					$blockWest === Block::WATER
				) {
					$this->absorbWater();
					$this->getLevel()->setBlock($this, Block::get(Block::SPONGE, 1), true, true);
					return Level::BLOCK_UPDATE_NORMAL;
				}
			}
			return false;
		}
		return true;
	}

	public function getName(){
		static $names = [
			0 => "Sponge",
			1 => "Wet Sponge"
		];
		return $names[$this->meta & 0x0f];
	}

	public function getDrops(Item $item){
		return [
			[$this->id, $this->meta & 0x0f, 1],
		];
	}

}
