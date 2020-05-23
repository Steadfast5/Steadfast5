<?php

namespace pocketmine\block;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Beacon as TileBeacon;
use pocketmine\tile\Tile;

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
				new IntTag("primary", 0),
				new IntTag("secondary", 0),
				new ByteTag("isMoveable", 0)
			]);
			Tile::createTile(Tile::BEACON, $level->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}
		return $result;
	}

	public function onActivate(Item $item, Player $player = null) {
		if ($player instanceof Player) {
			$tile = $this->level->getTile($this);
			if ($tile instanceof TileBeacon) {
				$top = $this->getSide(Vector3::SIDE_UP);
				if ($top->isTransparent() !== true) {
					return true;
				}
				$player->addWindow($tile->getInventory());
			}
		}
		return true;
	}

}
