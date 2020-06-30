<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\utils\TextFormat;

class EnchantCommand extends VanillaCommand {

	public function __construct($name) {
		parent::__construct(
			$name,
			"Adds enchantments on items",
			"/enchant <player> <enchantment ID> [level]"
		);
		$this->setPermission("pocketmine.command.enchant");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) < 2) {
			$sender->sendMessage("Usage: {%0}", [$this->usageMessage]);
			return true;
		}

		$player = $sender->getServer()->getPlayer($args[0]);

		if ($player === null) {
			$sender->sendMessage(TextFormat::RED . "That player cannot be found");
			return true;
		}

		$enchantId = (int) $args[1];
		$enchantLevel = isset($args[2]) ? (int) $args[2] : 1;

		$enchantment = Enchantment::getEnchantment($enchantId);
		if ($enchantment->getId() === Enchantment::TYPE_INVALID) {
			$sender->sendMessage("There is no such enchantment with ID {%0}", [$enchantId]));
			return false;
		}

		$enchantment->setLevel($enchantLevel);

		$item = $player->getInventory()->getItemInHand();

		if ($item->getId() <= 0) {
			$sender->sendMessage("The target doesn't hold an item");
			return false;
		}

		$item->addEnchantment($enchantment);
		$player->getInventory()->setItemInHand($item);

		$sender->sendMessage("Enchanting succeeded");
		return true;
	}

}
