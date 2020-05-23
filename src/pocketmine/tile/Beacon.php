<?php

namespace pocketmine\tile;

use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\inventory\BeaconInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\tile\Spawnable;
use pocketmine\Player;

class Beacon extends Spawnable {

	const TAG_PRIMARY = "primary";
	const TAG_SECONDARY = "secondary";

	private $inventory;
	private $primary = 0;
	private $secondary = 0;

	protected $currentTick = 0;
	protected $rangeBox;
	protected $minerals = [
		Block::IRON_BLOCK,
		Block::GOLD_BLOCK,
		Block::EMERALD_BLOCK,
		Block::DIAMOND_BLOCK
	];

	public function __construct(FullChunk $chunk, Compound $nbt) {
		parent::__construct($chunk, $nbt);
		if (isset($this->namedtag->primary)) {
			$this->primary = (int) $this->namedtag["primary"];
		}
		if (isset($this->namedtag->secondary)) {
			$this->secondary = (int) $this->namedtag["secondary"];
		}
		$this->scheduleUpdate();
		$this->rangeBox = new AxisAlignedBB($this->x, $this->y, $this->z, $this->x, $this->y, $this->z);
	}

	protected function readSaveData(Compound $nbt) {
		$this->primary = $nbt->getInt(self::TAG_PRIMARY, 0);
		$this->secondary = $nbt->getInt(self::TAG_SECONDARY, 0);
		$this->inventory = new BeaconInventory($this);
		$this->loadName($nbt);
		$this->loadItems($nbt);
	}

	protected function writeSaveData(Compound $nbt) {
		$nbt->setInt(self::TAG_PRIMARY, $this->primary);
		$nbt->setInt(self::TAG_SECONDARY, $this->secondary);
		$this->saveName($nbt);
		$this->saveItems($nbt);
	}

	protected function addAdditionalSpawnData(Compound $nbt) {
		$nbt->setInt(self::TAG_PRIMARY, $this->primary);
		$nbt->setInt(self::TAG_SECONDARY, $this->secondary);
		$this->addNameSpawnData($nbt);
	}

	public function getName() {
		return "Beacon";
	}

	public function getInventory() {
		return $this->inventory;
	}

	public function getRealInventory() {
		return $this->getInventory();
	}

	public function updateCompoundTag(Compound $nbt, Player $player) {
		if ($nbt->getString("id") !== Tile::BEACON) {
			return false;
		}
		$this->primary = $nbt->getInt(self::TAG_PRIMARY);
		$this->secondary = $nbt->getInt(self::TAG_SECONDARY);
		return true;
	}

	public function onUpdate() {
		if ($this->currentTick++ % 80 === 0) {
			if (($effectPrim = Effect::getEffect($this->primary)) !== null) {
				if (($pyramidLevels = $this->getPyramidLevels()) > 0) {
					$duration = 180 + $pyramidLevels * 40;
					$range = (10 + $pyramidLevels * 10);
					$effectPrim = new EffectInstance($effectPrim, $duration, $pyramidLevels == 4 && $this->primary == $this->secondary ? 1 : 0);
					$players = array_filter($this->level->getCollidingEntities($this->rangeBox->expandedCopy($range, $range, $range)), function (Entity $player) {
						return $player instanceof Player && $player->spawned;
					});
					foreach ($players as $player) {
						$player->addEffect($effectPrim);
						if ($pyramidLevels == 4 && $this->primary != $this->secondary) {
							$regen = new EffectInstance(Effect::getEffect(Effect::REGENERATION), $duration);
							$player->addEffect($regen);
						}
					}
				}
			}
		}
		return true;
	}

	protected function getPyramidLevels() {
		$allMineral = true;
		for ($i = 1; $i < 5; $i++) {
			for ($x = -$i; $x < $i + 1; $x++) {
				for ($z = -$i; $z < $i + 1; $z++) {
					$allMineral = $allMineral && in_array($this->level->getBlockAt($this->x + $x, $this->y - $i, $this->z + $z)->getId(), $this->minerals);
					if (!$allMineral) {
						return $i - 1;
					}
				}
			}
		}
		return 4;
	}

	public function getSpawnCompound() {
		return new Compound("", [
			new StringTag("id", Tile::BEACON),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			new IntTag("primary", (int) $this->primary),
			new IntTag("secondary", (int) $this->secondary),
			new ByteTag("isMovable", (int) $this->namedtag["isMovable"])
		]);
	}

	public function saveNBT() {
		parent::saveNBT();
		$this->namedtag->primary = new IntTag("primary", $this->primary);
		$this->namedtag->secondary = new IntTag("secondary", $this->secondary);
	}

}
