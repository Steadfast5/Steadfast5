<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\item\ItemBlock;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat;

class SetBlockCommand extends VanillaCommand {

	public function __construct($name) {
		parent::__construct(
			$name,
			"Changes a block to another block.",
			"setblock <position: x y z> <tileName: Block> [tileData: int] [destroy|keep|replace]"
		);
		$this->setPermission("pocketmine.command.setblock");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if (!$this->testPermission($sender)) {
			return true;
		}
		if (!isset($args[3])) {
			$sender->sendMessage("Usage: {%0}", [$this->usageMessage]);
		} else {
			$x = $args[0];
			$y = $args[1];
			v$z = $args[2];
			$block = explode(":", $args[3]);
			if (Item::fromString($block[0]) instanceof ItemBlock) {
				$level = $sender->getLevel();
				if (!isset($block[1])) {
					$block[1] = 0;
				}
				$level->setBlock(new Vector3($x, $y, $z), $block[0], $block[1]);
				Command::broadcastCommandMessage($sender, "Successfully set block", [$x, $y, $z, $block]);
			}
		}
	}

}
