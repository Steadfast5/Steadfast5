<?php

namespace pocketmine\block;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\tile\Beacon as BeaconTile;

class Beacon extends Solid {

	protected $id = self::BEACON;

	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getName() {
		return "Beacon";
	}

	public function getHardness() {
		return 3;
	}

	public function getLightLevel() {
		return 15;
	}

	public function getResistance() {
		return 15;
	}

	public function canBeActivated() {
		return true;
	}

	public function getDrops(Item $item) {
		return [
			[Item::BEACON, 0, 1]
		];
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$level = $this->getLevel();
		$result = $level->setBlock($this, $this, true, true);
		if ($result) {
			$nbt = new Compound("", [
				new StringTag("id", Tile::BEACON),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z),
				new IntTag("levels", 0),
				new IntTag("primary", 0),
				new IntTag("secondary", 0),
				new ByteTag("isMovable", 0)
			]);
			Tile::createTile(Tile::BEACON, $level->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}
		return $result;
	}

	public function onActivate(Item $item, Player $player = null) {
		$tile = $this->getLevel()->getTile($this);
		if (!($tile instanceof BeaconTile)) {
			$nbt = new Compound("", [
				new StringTag("id", Tile::BEACON),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z),
				new IntTag("levels", 0),
				new IntTag("primary", 0),
				new IntTag("secondary", 0),
				new ByteTag("isMovable", 0)
			]);
			$tile = Tile::createTile(Tile::BEACON, $this->level->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}
		$player->addWindow($tile->getInventory());
		return true;
	}

}
