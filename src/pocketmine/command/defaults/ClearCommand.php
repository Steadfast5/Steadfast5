<?php

namespace pocketmine\command\defaults;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ClearCommand extends VanillaCommand {

	public function __construct($name) {
		parent::__construct(
			$name,
			"Clears your / another player's inventory",
			"/clear [player]"
		);
		$this->setPermission("pocketmine.command.clear");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) >= 2) {
			$sender->sendMessage("Usage: {%0}", [$this->usageMessage]);
			return false;
		}

		if (count($args) === 1) {
			if (!$sender->hasPermission("pocketmine.command.clear")) {
				$sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
				return true;
			}

			switch ($args[0]) {
				case '@r':
					$players = $sender->getServer()->getOnlinePlayers();
					if (count($players) > 0) {
						$player = $players[array_rand($players)];
					} else {
						$sender->sendMessage("No players online");
						return true;
					}

					if ($player instanceof Player) {
						$sender->sendMessage("Cleared " . $this->clearTarget($player) . " items from " . $player->getName());
					}

					return true;
				case '@p':
					$player = $sender;
					if ($player instanceof Player) {
						$this->clearTarget($player);
					} else {
						$sender->sendMessage("You must run this command in-game");
					}

					return true;
				default:
					$player = $sender->getServer()->getPlayer($args[0]);
					if ($player instanceof Player) {
						$sender->sendMessage("Cleared " . $this->clearTarget($player) . " items from " . $player->getName());
					} else {
						$sender->sendMessage(TextFormat::RED . "That player cannot be found");
					}

					return true;
			}
		}

		if ($sender instanceof Player) {
			if (!$sender->hasPermission("pocketmine.command.clear")) {
				$sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
				return true;
			}

			$sender->sendMessage("Cleared " . $this->clearTarget($sender) . " items from " . $sender->getName());
		} else {
			$sender->sendMessage("Usage: {%0}", [$this->usageMessage]);

			return false;
		}

		return true;
	}

	private function clearTarget(Player $player) {
		$count = 0;
		$items = $player->getInventory()->getContents() + $player->getArmorInventory()->getContents();
		foreach ($items as $item) {
			$count += $item->getCount();
		}
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();

		return $count;
	}

}
