<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class ExpCommand extends VanillaCommand {

	public function __construct($name) {
		parent::__construct(
			$name,
			"Gives the specified player a certain amount of experience. Specify <amount>L to give levels instead, with a negative amount resulting in taking levels.",
			"/xp <amount> [player] OR /xp <amount>L [player]",
			[]
		);
		$this->setPermission("pocketmine.command.xp");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if (!$this->testPermission($sender)) {
			return true;
		}

    if (count($args) > 0) {
			$inputAmount = $args[0];
			$player = null;

			$isLevel = $this->endsWith($inputAmount, "l") || $this->endsWith($inputAmount, "L");
			if ($isLevel && strlen($inputAmount) > 1) {
				$inputAmount = substr($inputAmount, 0, strlen($inputAmount) - 1);
			}

			$amount = intval($inputAmount);
			$isTaking = $amount < 0;

			if ($isTaking) {
				$amount *= -1;
			}

			if (count($args) > 1) {
				$player = $sender->getServer()->getPlayer($args[1]);
			} elseif ($sender instanceof Player) {
				$player = $sender;
			}

			if ($player != null) {
				if ($isLevel) {
					if ($isTaking) {
						$player->addExpLevel(-$amount);
						Command::broadcastCommandMessage($sender, "Taken {%0} level(s) from {%1}", [$amount, $player->getName()]);
					} else {
						$player->addExpLevel($amount);
						Command::broadcastCommandMessage($sender, "Given  {%0} level(s) to {%1}", [$amount, $player->getName()]);
					}
				} else {
					if ($isTaking) {
						Command::broadcastCommandMessage($sender, TextFormat::RED . "Taking experience can only be done by levels, cannot give players negative experience points", []);
						return false;
					} else {
						$player->addExperience($amount);
						Command::broadcastCommandMessage($sender, "Given {%0} experience to {%1}", [$amount, $player->getName()]);
					}
				}
			} else {
				$sender->sendMessage(TextFormat::RED . "That player cannot be found");
				return false;
			}
			return true;
		}

		$sender->sendMessage(TextFormat::RED . "Usage: " . $this->usageMessage);
		return false;
	}

	public function endsWith($haystack, $needle) {
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}

}
