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

use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\item\Armour;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\tile\Cauldron as TileCauldron;
use pocketmine\tile\Tile;
use pocketmine\utils\Color;
use pocketmine\Player;
use pocketmine\Server;

class Cauldron extends Solid {

	protected $id = self::CAULDRON;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getHardness(){
		return 2;
	}

	public function getName(){
		return "Cauldron";
	}

	public function getToolType() {
		return Tool::TYPE_PICKAXE;
	}

	public function canBeActivated() {
		return true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$nbt = Tile::createNBT($this);
		if ($item->hasCustomBlockData()) {
			foreach ($item->getCustomBlockData() as $key => $v) {
				$nbt->{$key} = $v;
			}
		}
		Tile::createTile(Tile::CAULDRON, $this->getLevel(), $nbt);
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}

	public function onActivate(Item $item, Player $player = null) {
		$tile = $this->getLevel()->getTile($this);
		if (!($tile instanceof TileCauldron)) {
			return false;
		}
		switch ($item->getId()) {
			case Item::BUCKET:
				if ($item->getDamage() === 0) {
					if (!$this->isFull() or $tile->hasCustomColor() or $tile->hasPotion()) {
						break;
					}
					$bucket = clone $item;
					$bucket->setDamage(8);
					Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerBucketFillEvent($player, $this, 0, $item, $bucket));
					if (!$ev->isCancelled()) {
						if ($player->isSurvival()) {
							$player->getInventory()->setItemInHand($ev->getItem());
						}
						$this->meta = 0;
						$this->getLevel()->setBlock($this, $this, true);
						$tile->clearCustomColor();
						$ev = new LevelEventPacket();
						$ev->data = 0;
						$ev->evid = LevelEventPacket::EVENT_CAULDRON_TAKE_WATER;
						$ev->position = $this->add(0.5, 0, 0.5);
						$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
					}
				} elseif ($item->getDamage() === 8) {
					if ($this->isFull() && !$tile->hasCustomColor() && !$tile->hasPotion()) {
						break;
					}
					$bucket = clone $item;
					$bucket->setDamage(0);
					Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerBucketEmptyEvent($player, $this, 0, $item, $bucket));
					if (!$ev->isCancelled()) {
						if ($player->isSurvival()) {
							$player->getInventory()->setItemInHand($ev->getItem());
						}
						if ($tile->hasPotion()) {
							$this->meta = 0;
							$tile->setPotionId(-1);
							$tile->setSplashPotion(false);
							$tile->clearCustomColor();
							$this->getLevel()->setBlock($this, $this, true);
							$ev = new LevelEventPacket();
							$ev->data = 0;
							$ev->evid = LevelEventPacket::EVENT_CAULDRON_EXPLODE;
							$ev->position = $this->add(0.5, 0, 0.5);
							$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
						} else {
							$this->meta = 6;
							$tile->clearCustomColor();
							$this->getLevel()->setBlock($this, $this, true);
							$ev = new LevelEventPacket();
							$ev->data = 0;
							$ev->evid = LevelEventPacket::EVENT_CAULDRON_FILL_WATER;
							$ev->position = $this->add(0.5, 0, 0.5);
							$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
						}
						$this->update();
					}
				}
				break;
			case Item::DYE:
				if ($tile->hasPotion()) {
					break;
				}
				$color = Color::getDyeColor($item->getDamage());
				if ($tile->hasCustomColor()) {
					$color = Color::averageColor($color, $tile->getCustomColor());
				}
				if ($player->isSurvival()) {
					$item->setCount($item->getCount() - 1);
//					if ($item->getCount() <= 0) {
//						$player->getInventory()->setItemInHand(Item::get(Item::AIR));
//					}
				}
				$tile->setCustomColor($color);
				$ev = new LevelEventPacket();
				$ev->data = $color->toRGB();
				$ev->evid = LevelEventPacket::EVENT_CAULDRON_ADD_DYE;
				$ev->position = $this->add(0.5, 0, 0.5);
				$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				$this->update();
				break;
			case Item::LEATHER_CAP:
			case Item::LEATHER_TUNIC:
			case Item::LEATHER_PANTS:
			case Item::LEATHER_BOOTS:
				if ($this->isEmpty()) {
					break;
				}
				if ($tile->hasPotion()) {
					break;
				}
				if ($tile->hasCustomColor()) {
					$this->meta;
					$this->getLevel()->setBlock($this, $this, true);
					$newItem = clone $item;
					$newItem->setCustomColor($tile->getCustomColor());
					$player->getInventory()->setItemInHand($newItem);
					$ev = new LevelEventPacket();
					$color = $tile->getCustomColor();
					$ev->data = $color->toRGB();
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_DYE_ARMOR;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
					if ($this->isEmpty()) {
						$tile->clearCustomColor();
					}
				} else {
					$this->meta;
					$this->getLevel()->setBlock($this, $this, true);
					$newItem = clone $item;
					$newItem->removeNamedTagEntry(Armor::TAG_CUSTOM_COLOR);
					$player->getInventory()->setItemInHand($newItem);
					$ev = new LevelEventPacket();
					$color = $item->getCustomColor();
					$ev->data = $color->toRGB();
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_CLEAN_ARMOR;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				}
				break;
			case Item::POTION:
			case Item::SPLASH_POTION:
				if (!$this->isEmpty() && (($tile->getPotionId() !== $item->getDamage() && $item->getDamage() !== Potion::WATER_BOTTLE) or
					($item->getId() === Item::POTION && $tile->getSplashPotion()) or
					($item->getId() === Item::SPLASH_POTION && !$tile->getSplashPotion()) && $item->getDamage() !== 0 or
					($item->getDamage() === Potion::WATER_BOTTLE && $tile->hasPotion()))
				) {
					$this->meta = 0x00;
					$this->getLevel()->setBlock($this, $this, true);
					$tile->setPotionId(-1);
					$tile->setSplashPotion(false);
					$tile->clearCustomColor();
					if ($player->isSurvival()) {
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$ev = new LevelEventPacket();
					$ev->data = 0;
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_EXPLODE;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				} elseif ($item->getDamage() === Potion::WATER_BOTTLE) {
					$this->meta += 2;
					if ($this->meta > 0x06) {
						$this->meta = 0x06;
					}
					$this->getLevel()->setBlock($this, $this, true);
					if ($player->isSurvival()) {
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$tile->setPotionId(0);
					$tile->setSplashPotion(false);
					$tile->clearCustomColor();
					$ev = new LevelEventPacket();
					$ev->data = 0;
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_FILL_POTION;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				} elseif (!$this->isFull()) {
					$this->meta += 2;
					if ($this->meta > 0x06) {
						$this->meta = 0x06;
					}
					$tile->setPotionId($item->getDamage());
					$tile->setSplashPotion($item->getId() === Item::SPLASH_POTION);
					$tile->clearCustomColor();
					$this->getLevel()->setBlock($this, $this, true);
					if ($player->isSurvival()) {
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$color = Potion::getColor($item->getDamage());
					$ev = new LevelEventPacket();
					$color = new Color($color[0], $color[1], $color[2]);
					$ev->data = $color->toRGB();
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_FILL_POTION;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				}
				break;
			case Item::GLASS_BOTTLE:
				if ($this->meta < 2) {
					break;
				}
				if ($tile->hasPotion()) {
					$this->meta -= 2;
					if ($tile->getSplashPotion() === true) {
						$result = Item::get(Item::SPLASH_POTION, $tile->getPotionId());
					} else {
						$result = Item::get(Item::POTION, $tile->getPotionId());
					}
					if ($this->isEmpty()) {
						$tile->setPotionId(-1);
						$tile->setSplashPotion(false);
						$tile->clearCustomColor();
					}
					$this->getLevel()->setBlock($this, $this, true);
					$this->addItem($item, $player, $result);
					$color = Potion::getColor($result->getDamage());
					$color = new Color($color[0], $color[1], $color[2]);
					$ev = new LevelEventPacket();
					$ev->data = $color->toRGB();
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_FILL_POTION;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				} else {
					$this->meta -= 2;
					$this->getLevel()->setBlock($this, $this, true);
					if ($player->isSurvival()) {
						$result = Item::get(Item::POTION, Potion::WATER_BOTTLE);
						$this->addItem($item, $player, $result);
					}
					$ev = new LevelEventPacket();
					$color = $tile->getCustomColor();
					$ev->data = $color->toRGB();
					$ev->evid = LevelEventPacket::EVENT_CAULDRON_TAKE_POTION;
					$ev->position = $this->add(0.5, 0, 0.5);
					$this->getLevel()->addChunkPacket($this->x >> 4, $this->z >> 4, $ev);
				}
				break;
		}
		return true;
	}

	public function getDrops(Item $item){
		return [
			[Item::CAULDRON, 0, 1]
		];
	}
}
