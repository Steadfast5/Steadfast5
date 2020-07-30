<?php

namespace pocketmine\block;

use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\MovingObjectPosition;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\Player;

class Observer extends Solid {
/*
	protected $id = self::OBSERVER;
	protected $currentStatus = self::STATUS_IS_DEACTIVATED;

	const STATUS_IS_DEACTIVATED = -1;
	const STATUS_QUED_FOR_DEACTIVATION = 0;
	const STATUS_IS_ACTIVATED = 1;

	const TYPE_BLOCK_HASH = 0;
	const TYPE_BLOCK_OBJECT = 1;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getName(){
		return "Observer";
	}

	public function getHardness(){
		return 3.5;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function isCharged(){
		if ($this->currentStatus != self::STATUS_IS_DEACTIVATED) {
			return true;
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz,Player $player = null) {
		$this->meta = Vector3::getOppositeSide($face);
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}

	public function onUpdate($type, $fromBlock = null) {
		if ($type == Level::BLOCK_UPDATE_EVENT) {
			if ($fromBlock != null) {
				$this->onUpdateRecieve(self::TYPE_BLOCK_OBJECT, $fromBlock);
			}
		}
		if ($type === Level::BLOCK_UPDATE_SCHEDULED) {
			if ($this->currentStatus != self::STATUS_IS_DEACTIVATED) {
				if ($this->currentStatus == self::STATUS_IS_ACTIVATED) {
					$this->currentStatus = self::STATUS_QUED_FOR_DEACTIVATION;
					$this->getLevel()->scheduleUpdate($this, 2);
				} elseif ($this->currentStatus == self::STATUS_QUED_FOR_DEACTIVATION) {
					$this->currentStatus = self::STATUS_IS_DEACTIVATED;
				}
			}
		}
	}
*/
}
