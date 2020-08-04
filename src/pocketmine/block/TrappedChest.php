<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Tile;

class TrappedChest extends Chest {

	protected $id = self::TRAPPED_CHEST;

	/*public function __construct($meta = 0) {
		$this->meta = $meta;
	}

	public function getBoundingBox() {
		if ($this->boundingBox === null) {
			$this->boundingBox = $this->recalculateBoundingBox();
		}
		return $this->boundingBox;
	}

	public function isSolid() {
		return true;
	}

	public function canBeFlowedInto() {
		return false;
	}

	public function canBeActivated() {
		return false;
	}

	public function getHardness() {
		return 2.5;
	}

	public function getResistance() {
		return $this->getHardness() * 5;
	}

	public function getName() {
		return "Trapped Chest";
	}

	public function getToolType() {
		return Tool::TYPE_AXE;
	}

	public function recalculateBoundingBox() {
		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.9475,
			$this->z + 0.9375
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		];

		$chest = null;
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

		for ($side = 2; $side <= 5; ++$side) {
			if (($this->meta === 4 || $this->meta === 5) && ($side === 4 || $side === 5)) {
				continue;
			} elseif (($this->meta === 3 || $this->meta === 2) && ($side === 2 || $side === 3)) {
				continue;
			}
			$c = $this->getSide($side);
			if ($c instanceof Chest && $c->getDamage() === $this->meta) {
				$tile = $this->getLevel()->getTile($c);
				if ($tile instanceof TileChest && !$tile->isPaired()) {
					$chest = $tile;
					break;
				}
			}
		}

		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new Compound("", [
			new Enum("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);

		if ($item->hasCustomName()) {
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if ($item->hasCustomBlockData()) {
			foreach ($item->getCustomBlockData() as $key => $v) {
				$nbt->{$key} = $v;
			}
		}

		$tile = Tile::createTile("Chest", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		if ($chest instanceof TileChest && $tile instanceof TileChest) {
			$chest->pairWith($tile);
			$tile->pairWith($chest);
		}

		return true;
	}

	public function onBreak(Item $item) {
		$t = $this->getLevel()->getTile($this);
		if ($t instanceof TileChest) {
			$t->unpair();
		}
		$this->getLevel()->setBlock($this, new Air(), true, true);

		return true;
	}

	public function onActivate(Item $item, Player $player = null) {
		if ($player instanceof Player) {
			$top = $this->getSide(1);
			if ($top->isTransparent() !== true) {
				return true;
			}

			$t = $this->getLevel()->getTile($this);
			$chest = null;
			if ($t instanceof TileChest) {
				$chest = $t;
			} else {
				$nbt = new Compound("", [
					new Enum("Items", []),
					new StringTag("id", Tile::CHEST),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$chest = Tile::createTile("Chest", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}

			if (isset($chest->namedtag->Lock) && $chest->namedtag->Lock instanceof StringTag) {
				$chestName = $chest->namedtag->Lock->getValue();
				if (!empty($chestName) && $chestName !== $item->getCustomName()) {
					return true;
				}
			}

//			if ($player->isCreative()) {
//				return true;
//			}
			$player->addWindow($chest->getInventory());
		}

		return true;
	}

	public function getDrops(Item $item) {
		return [
			[$this->id, 0, 1],
		];
	}

	public function onUpdate($type, $deep) {
		if (!Block::onUpdate($type, $deep)) {
			return false;
		}
		if ($type == Level::BLOCK_UPDATE_WEAK) {
			$blockBelowId = $this->level->getBlockIdAt($this->x, $this->y - 1, $this->z);
			if ($blockBelowId == self::HOPPER_BLOCK) {
				$blockBelowHopperId = $this->level->getBlockIdAt($this->x, $this->y - 2, $this->z);
				if ($blockBelowHopperId == self::CHEST) {
					$anotherChest = $this->level->getBlock(new Vector3($this->x, $this->y - 2, $this->z));
					$chestInventory = $this->level->getTile($this)->getInventory();
					$chestItems = $chestInventory->getContents();
					$anotherChestInventory = $this->level->getTile($anotherChest)->getInventory();
					foreach ($chestItems as $index => $item) {
						if ($item->getId() != self::AIR) {
							if (empty($anotherChestInventory->addItem($item))) {
								unset($chestItems[$index]);
							} else {
								break;
							}
						}
					}
					$chestInventory->setContents($chestItems);
				}
			}
		}
	}*/

}
