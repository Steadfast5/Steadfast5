<?php

namespace pocketmine\tile;

use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\inventory\BeaconInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\scheduler\CallbackTask;
use pocketmine\tile\Spawnable;
use pocketmine\Player;

class Beacon extends Spawnable implements InventoryHolder {

	const POWER_LEVEL_MAX = 4;
	const PYRAMID_BLOCKS = [Block::DIAMOND_BLOCK, Block::EMERALD_BLOCK, Block::GOLD_BLOCK, Block::IRON_BLOCK];

	private $tier = 0;
	private $primary = 0;
	private $secondary = 0;
	private $movable = true;

	protected $inventory = null;

	public function __construct(FullChunk $chunk, Compound $nbt) {
		parent::__construct($chunk, $nbt);
		$this->inventory = new BeaconInventory($this);
		if (isset($this->namedtag->primary)) {
			$this->primary = (int) $this->namedtag["primary"];
		}
		if (isset($this->namedtag->secondary)) {
			$this->secondary = (int) $this->namedtag["secondary"];
		}
		$this->scheduleUpdate();
	}

	public function onUpdate() {
		if ($this->closed) {
			return false;
		}
		$this->checkViewers();
		$currentTick = $this->getLevel()->getServer()->getTick();
		if ($this->ticks + 80 <= $currentTick) {
			$this->ticks = $currentTick;
//			$level = $this->calculatePowerLevel();
			$levels = $this->getLayers();
			if ($this->tier > $levels) {
				$this->tier = 0;
				$this->spawnToAll();
			} else {
				if ($this->tier < $levels) {
					$this->tier = $levels;
					$this->spawnToAll();
				}
				$duration = 9 + ($levels * 2);
				$range = 10 + ($levels * 10);
				foreach ($this->level->getPlayers() as $player) {
					if ($player->distance($this) <= $range) {
						$effectId = $this->primary;
						if ($effectId !== 0) {
							$player->addEffect(Effect::getEffect($effectId)->setAmplifier(0)->setDuration($duration * 20 ^ 2)->setVisible(false));
						}
						$effectId = $this->secondary;
						if ($effectId !== 0) {
							if ($this->secondary == $this->primary) {
								$player->addEffect(Effect::getEffect($effectId)->setAmplifier(1)->setDuration($duration * 20 ^ 2)->setVisible(false));
							} else {
								$player->addEffect(Effect::getEffect($effectId)->setAmplifier(0)->setDuration($duration * 20 ^ 2)->setVisible(false));
							}
						}
					}
				}
			}
		}
		return true;
	}

	private function checkViewers() {
		$viewers = $this->level->getChunkPlayers($this->getFloorX() >> 4, $this->getFloorZ() >> 4);
		$names = [];
		newViewers = [];
		foreach ($viewers as $player) {
			if (!in_array($player->getName(), $this->viewers)) {
				$newViewers[] = $player->getName();
			}
			$names[] = $player->getName();
		}
		foreach ($newViewers as $name) {
			$players = level->getServer()->getPlayerExact($name);
			$glass = Block::get(Block::GLASS);
			$glass->position($this->asPosition);
			$beacon = Block::get(Block::BEACON);
			$beacon->position($this->asPosition);
			if ($player instanceof Player) {
				Server::getInstance()->getScheduler()->scheduleDelayedTask(new CallbackTask(function() use ($player, $glass, $beacon) {
					if (!($this->level instanceof Level)) {
						return;
					}
					$this->getLevel()->sendBlocks([$player], [$glass], UpdateBlockPacket::FLAG_PRIORITY);
					$this->getLevel()->sendBlocks([$player], [$beacon], UpdateBlockPacket::FLAG_PRIORITY);
				}), 20);
			}
		}
		$this->viewers = $name;
	}

	public function getLayers() {
		$layers = 0;
		if ($this->checkShape($this->getSide(0), 1)) {
			$layers++;
		} else {
			return $layers;
		}
		if ($this->checkShape($this->getSide(0, 2), 2)) {
			$layers++;
		} else {
			return $layers;
		}
		if ($this->checkShape($this->getSide(0, 3), 3)) {
			$layers++;
		} else {
			return $layers;
		}
		if ($this->checkShape($this->getSide(0, 4), 4)) {
			$layers++;
		} else {
			return $layers;
		}
	}

	public function checkShape(Vector3 $pos, $layer = 1) {
		for ($x = $pos->x - $layer; $x <= $pos->x + $layer; $x++) {
			for ($z = $pos->z - $layer; $z <= $pos->z + $layer; $z++) {
				if (!in_array($this->level->getBlockIdAt($x, $pos->y, $z), self::PYRAMID_BLOCKS)) {
					return false;
				}
			}
		}
		return true;
	}

	public function isSolidAbove() {
		if ($this->y === $this->getLevel()->getHightestBlockAt($this->x, $this->z)) {
			return false;
		}
		$tmpVector = new Vector3();
		for ($i = $this->y; $i < 128; $i++) { // TODO: allow for leveldb worlds
			$tmpVector->setComponents($this->x, $i, $this->z);
			$block = $this->getLevel()->getBlock($tmpVector)
			if ($block->isSolid() && $block->getId() !== Block::BEACON) {
				return true;
			}
		}
		return false;
	}

	public function calculatePowerLevel() {
		$tileX = $this->getFloorX();
		$tileY = $this->getFloorY();
		$tileZ = $this->getFloorZ();
		for ($powerLevel = 1; $powerLevel <= self::POWER_LEVEL_MAX; $powerLevel++) {
			$queryY = $tileY - $powerLevel;
			for ($queryX = $tileX - $powerLevel; $queryX <= $tileX + $powerLevel; $queryX++) {
				for ($queryZ = $tileZ - $powerLevel; $queryZ <= $tileZ + $powerLevel; $queryZ++) {
					$block = $this->level->getBlockIdAt($queryX, $queryY, $queryZ);
					if ($block != Block::IRON_BLOCK && $block != Block::GOLD_BLOCK && $block != Block::EMERALD_BLOCK && $block != Block::DIAMOND_BLOCK) {
						return $powerLevel - 1;
					}
				}
			}
		}
		return self::POWER_LEVEL_MAX;
	}

	public function spawnToAll() {
		if ($this->closed) {
			return;
		}
		parent::spawnToAll();
	}

	public function close() {
		if ($this->closed === false) {
			foreach ($this->inventory->getViewers() as $player) {
				$player->removeWindow($this->inventory);
			}
		}
	}

	/**
	 * 
	 * @return BeaconInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}

	public function getSpawnCompound() {
		return new Compound("", [
			new StringTag("id", Tile::BEACON),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			new IntTag("levels", (int) $this->getLayers()),
			new IntTag("primary", (int) $this->primary),
			new IntTag("secondary", (int) $this->secondary),
			new ByteTag("isMovable", (int) $this->namedtag["isMovable"])
		]);
	}

	public function saveNBT() {
		parent::saveNBT();
		$this->namedtag->tier = new IntTag("levels", $this->tier);
		$this->namedtag->primary = new IntTag("primary", $this->primary);
		$this->namedtag->secondary = new IntTag("secondary", $this->secondary);
		$this->namedtag->isMovable = new ByteTag("isMovable", 1);
	}

	public function updateNBT() {
		if ($this->namedtag["id"] !== Tile::BEACON) {
			return false;
		}
		$this->namedtag->tier = new IntTag("levels", $this->namedtag["levels"]);
		$this->namedtag->primary = new IntTag("primary", $this->namedtag["primary"]);
		$this->namedtag->secondary = new IntTag("secondary", $this->namedtag["secondary"]);
		$this->namedtag->isMovable = new ByteTag("isMovable", $this->namedtag["isMovable"]);
		return true;
	}

	public function getTier() {
		return $this->tier;
	}

	public function getPrimary() {
		return $this->primary;
	}

	public function setPrimary($primary) {
		$this->primary = $primary;
		return $this;
	}

	public function getSecondary() {
		return $this->secondary;
	}

	public function setSecondary($secondary) {
		$this->secondary = $secondary;
		return $this;
	}

	public function isMovable() {
		return $this->movable;
	}

	public function setMovable($movable) {
		$this->movable = $movable;
		return $this;
	}

}
