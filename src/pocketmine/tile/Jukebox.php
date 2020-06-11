<?php

namespace pocketmine\tile;

use pocketmine\event\record\RecordPlayEvent;
use pocketmine\event\record\RecordStopEvent;
use pocketmine\item\Item;
use pocketmine\item\Record;
use pocketmine\level\particle\GenericParticle;
use pocketmine\nbt\tag\Compound;
use pocketmine\network\protocol\TextPacket;
use pocketmine\network\protocol\LevelSoundEventPacket;
use pocketmine\tile\Spawnable;
use pocketmine\Player;
use pocketmine\Server;
use mt_rand;
use mt_getrandmax;

class Jukebox extends Spawnable {

	public $hasRecord = false;
	public $record = null;

	private static $instance = null;

	public function getName() {
		return "Jukebox";
	}

	public function handleInteract(Item $item, Player $player = null) {
		if ($this->hasRecord) {
			$this->updateRecord();
		} else {
			if ($item instanceof Record) {
				$this->updateRecord($item, $player);
			}
		}
		$this->scheduleUpdate();
	}

	public function handleBreak(Item $item, Player $player) {
		if ($this->hasRecord) {
			$this->updateRecord();
		}
	}

	public static function getInstance() {
		return self::$instance;
	}

	public function updateRecord(Item $record = null, Player $player = null) {
		if ($record == null) {
			$ev = new RecordStopEvent(self::getInstance(), $this->getBlock(), $this->record, $player);
			Server::getInstance()->getPluginManager()->callEvent($ev);
			if ($ev->isCancelled()) {
				return;
			}
			$this->dropRecord();
		} else {
			$ev = new RecordPlayEvent(self::getInstance(), $this->getBlock(), $record, $player);
			Server::getInstance()->getPluginManager()->callEvent($ev);
			if ($ev->isCancelled()) {
				return;
			}

			$player->getInventory()->removeItem($record);

			$this->record = $record;
			$this->hasRecord = true;

			$this->getLevel()->broadcastLevelSoundEvent($this, $record->getSoundId());

			$plug = self::getInstance();

			$plug->sendForm($player);

			if ($plug->cfg->get("popup") === true) {
				$msg = str_replace("{NAME}",$record->getSoundName(),$plug->cfg->get("popup_text"));
				$pk = new TextPacket();
				$pk->type = TextPacket::TYPE_JUKEBOX_POPUP;
				$pk->message = $msg;
				$player->dataPacket($pk);
			}
		}
		$this->onChanged();
	}

	public function dropRecord() {
		if ($this->hasRecord) {
			$this->getLevel()->dropItem($this->asVector3(), $this->record);
			$this->hasRecord = false;
			$this->record = null;
			$this->stopSound();
		}
	}

	public function stopSound() {
        $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_STOP_RECORD);
	}

	public function onUpdate() {
		$plug = self::getInstance();
		if ($this->hasRecord && $plug->cfg->get("particles") === true) {
			if (Server::getInstance()->getTick() % $plug->cfg->get("particles_ticks") == 0 ) {
				$this->level->addParticle(new GenericParticle($this->add($this->randomFloat(0.3, 0.7), $this->randomFloat(1.2, 1.6), $this->randomFloat(0.3, 0.7)), 36));
			}
		}
		return true;
	}

	public function readSaveData(Compound $nbt) {
		if ($nbt->hasTag("Record")) {
			$this->record = Item::nbtDeserialize($nbt->getCompound("Record"));
		}
	}

	protected function writeSaveData(Compound $nbt) {
		if ($this->record !== null) {
			$nbt->setTag($this->record->nbtSerialize(-1, "Record"));
		}
	}

	protected function addAdditionalSpawnData(Compound $nbt) {

	}

	private function randomFloat($min = 0, $max = 1) {
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}

}
