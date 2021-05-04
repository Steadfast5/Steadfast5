<?php

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Obsidian;
use pocketmine\entity\Entity;
use pocketmine\entity\EnderCrystal;
use pocketmine\level\Level;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EndCrystal extends Item {

	public function __construct($meta = 0) {
		parent::__construct(self::END_CRYSTAL, $meta, "End Crystal");
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz) {
		if ($level->getBlock($target->down()) instanceof Obsidian) {
			$crystal = new EnderCrystal($level, new Compound("", [
				"Pos" => new Enum("Pos", [
					new DoubleTag("", $block->getX() + 0.5),
					new DoubleTag("", $block->getY() + 0.5),
					new DoubleTag("", $block->getZ() + 0.5)
				]),
				"Motion" => new Enum("Motion", [
					new DoubleTag("", 0),
					new DoubleTag("", 0),
					new DoubleTag("", 0)
				]),
				"Rotation" => new Enum("Rotation", [
					new FloatTag("", 0),
					new FloatTag("", 0)
				])
			]));
			$crystal->spawnToAll();

			if ($player->isSurvival()) {
				$this->pop();
			}

			return true;
		}
		return false;
	}

}
