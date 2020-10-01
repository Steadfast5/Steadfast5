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
use pocketmine\tile\JukeboxTile;
use pocketmine\level\sound\RecordSound;
use pocketmine\level\sound\RecordStopSound;
use pocketmine\Player;

class Jukebox extends Solid {

	private $record = null;

	public function __construct($meta = 0){
		$this->id = self::JUKEBOX;
		$this->meta = $meta;
	}

	public function getFuelTime() {
		return 300;
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

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		if ($player instanceof Player) {
			if ($this->record !== null) {
				$this->getLevel()->dropItem($block->add(0.5, 1, 0.5), $this->record); // eject record
				$this->record = null;
				$this->stopSound();
			} elseif ($item instanceof Record) {
				$player->sendJukeboxPopup("Now playing: ", [$item->getRecordType()->getTranslationKey()]);
				$this->insertRecord($item->pop());
			}
		}
		$this->getLevel()->setBlock($this, $this, true, true);
		return true;
	}

	private function getRecord() {
		return $this->record;
	}

	private function insertRecord(Record $record) {
		if ($record === null) {
			$this->record = $record;
			$this->startSound();
		}
	}

	private function startSound() {
		if ($this->record !== null) {
			$this->getLevel()->addSound(new RecordSound($this->record->getRecordType()));
		}
	}

	private function stopSound() {
		$this->getLevel()->addSound(new RecordStopSound());
	}

	/*public function onActivate(Item $item, Player $player = null) {
		if (!$player instanceof Player) {
			return false;
		}
		$this->verifyTile($item, $player);
		$tile = $this->getLevel()->getTile($this);
		$tile->handleInteract($item, $player);
		return true;
	}*/

	public function onBreak(Item $item, Player $player = null) {
		$this->stopSound();
		return parent::onBreak($item, $player);
	}

	public function getDrops(Item $item){
		return [
			[Item::JUKEBOX, 0, 1]
		];
	}

	// TODO: implement redstone effects

}
