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

class NoteBlock extends Solid {

	const PIANO_OR_HARP = 0;
	const BASS_DRUM = 1;
	const SNARE_DRUM = 2;
	const CLICKS_AND_STICKS = 3;
	const BASS_GUITAR = 4;

	const NOTEBLOCK = 25;
	const STONE_PRESSURE_PLATE = 70;
	const WOODEN_PRESSURE_PLATE = 72;
	const SOULSAND = 88;
	const DAYLIHT_SENSOR = 151;
	const INVERTED_DAYLIHT_SENSOR = 178;
	const SPRUCE_WOODEN_DOOR_BLOCK = 193;
	const BIRCH_WOODEN_DOOR_BLOCK = 194;
	const JUNGLE_WOODEN_DOOR_BLOCK = 195;
	const ACACIA_WOODEN_DOOR_BLOCK = 196;
	const DARK_OAK_WOODEN_DOOR_BLOCK = 197;

	protected $id = self::NOTE_BLOCK;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getName() {
		return "Note Block";
	}

	public function getHardness() {
		return 0.8;
	}

	public static function runNoteBlockSound(Position $pos, $pitch, $type = NoteBlock::PIANO_OR_HARP, $players = null) {
		if (!is_array($players)) {
			if ($players instanceof Player) {
				$players = [$players];
			} elseif ($players == null) {
				$players = $pos->getLevel()->getChunkPlayers($pos->x >> 4, $pos->z >> 4);
			} else {
				return false;
			}
		}
		$pk = new BlockEventPacket();
		$pk->x = $pos->x;
		$pk->y = $pos->y;
		$pk->z = $pos->z;
		$pk->case1 = $type;
		$pk->case2 = $pitch;
		$setNoteBlockPk = new UpdateBlockPacket();
		$setNoteBlockPk->records[] = [$pos->x, $pos->z, $pos->y, 25, 0, UpdateBlockPacket::FLAG_NONE];
		$realBlock = $pos->getLevel()->getBlock($pos);
		$setRealBlockPk = new UpdateBlockPacket();
		$setRealBlockPk->records[] = [$pos->x, $pos->z, $pos->y, $realBlock->getID(), $realBlock->getDamage(), UpdateBlockPacket::FLAG_NONE];
		Server::getInstance()->batchPackets($players, [$setNoteBlockPk, $pk, $setRealBlockPk], false);
		return true;
	}

	public function canBeActivated() {
		return true;
	}

	public function onActivated(Item $item, Player $player = null) {
		$type = self::PIANO_OR_HARP;
		$side = $this->getSide(0);
 		switch ($side->getID()) {
			case Block::STONE:
			case Block::COBBLESTONE:
			case Block::COBBLE_STAIRS:
			case Block::BEDROCK:
 			case Block::GOLD_ORE:
			case Block::IRON_ORE:
			case Block::COAL_ORE:
			case Block::LAPIS_ORE:
			case Block::DIAMOND_ORE:
			case Block::REDSTONE_ORE:
			case Block::EMERALD_ORE:
 			case Block::GLOWING_REDSTONE_ORE:
			case Block::FURNACE:
			case Block::BURNING_FURNACE:
			case Block::BRICKS:
			case Block::BRICK_STAIRS:
			case Block::STONE_BRICK:
			case Block::STONE_BRICK_STAIRS:
			case Block::NETHERRACK:
			case Block::COBBLE_WALL:
			case Block::STONECUTTER:
			case Block::MOSS_STONE:
			case Block::OBSIDIAN:
			case Block::SANDSTONE:
			case Block::END_STONE:
			case Block::MONSTER_SPAWNER:
 			case Block::END_PORTAL_FRAME:
 			case Block::QUARTZ_BLOCK:
			case Block::QUARTZ_STAIRS:
			case Block::NETHER_BRICKS:
			case Block::NETHER_BRICKS_STAIRS:
			case Block::ENCHANT_TABLE:
 			case self::STONE_PRESSURE_PLATE:
				$type = self::BASS_DRUM;
				break;
 			case Block::SAND:
			case Block::GRAVEL:
			case self::SOULSAND:
				$type = self::SNARE_DRUM;
				break;
			case Block::GLASS:
			case Block::GLASS_PANEL:
			case Block::GLOWSTONE:
 				$type = self::CLICKS_AND_STICKS;
				break;
			case Block::WOOD:
			case Block::WOOD2:
			case Block::PLANK:
			case Block::SPRUCE_WOOD_STAIRS:
			case Block::BIRCH_WOOD_STAIRS:
			case Block::JUNGLE_WOOD_STAIRS:
			case Block::DOUBLE_WOOD_SLAB:
			case Block::ACACIA_WOOD_STAIRS:
			case Block::DARK_OAK_WOOD_STAIRS:
			case Block::WOOD_STAIRS:
			case Block::BOOKSHELF:
			case Block::CHEST:
			case Block::WORKBENCH:
			case Block::SIGN_POST:
			case Block::WALL_SIGN:
			case Block::WOOD_DOOR_BLOCK:
			case self::SPRUCE_WOODEN_DOOR_BLOCK:
			case self::BIRCH_WOODEN_DOOR_BLOCK:
			case self::JUNGLE_WOODEN_DOOR_BLOCK:
			case self::ACACIA_WOODEN_DOOR_BLOCK:
			case self::DARK_OAK_WOODEN_DOOR_BLOCK:
			case Block::TRAPDOOR:
			case Block::FENCE:
			case Block::FENCE_GATE:
			case Block::FENCE_GATE_SPRUCE:
			case Block::FENCE_GATE_BIRCH:
			case Block::FENCE_GATE_JUNGLE:
			case Block::FENCE_GATE_DARK_OAK:
			case Block::FENCE_GATE_ACACIA:
			case Block::WOOD_SLAB:
			case Block::BROWN_MUSHROOM:
			case Block::RED_MUSHROOM:
 			case self::NOTEBLOCK:
 			case self::WOODEN_PRESSURE_PLATE:
 			case self::DAYLIHT_SENSOR:
 			case self::INVERTED_DAYLIHT_SENSOR:
				$type = self::BASS_GUITAR;
				break;
			case Block::SLAB:
			case Block::DOUBLE_SLAB:
				if ($side->getDamage() == 2) {
					$type = self::BASS_GUITAR;
				} else {
					$type = self::BASS_DRUM;
				}
				break;
		}
		NoteBlock::runNoteBlockSound($this, $this->meta, $type);
		$this->meta = ($this->meta + 1) % 25;
		return true;
	}

	public function getToolType() {
		return Tool::TYPE_AXE;
	}

	public function getDrops(Item $item) {
		return [
			Item::NOTE_BLOCK, 0, 1
		];
	}

}
