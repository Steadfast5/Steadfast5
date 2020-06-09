<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\level\weather\Weather;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class WeatherCommand extends VanillaCommand {

	public function __construct($name) {
		parent::__construct(
			$name,
			"Set weather for world",
			"/weather <world-name weather|weather (rain|sunny|clear)>",
		);
		$this->setPermission("pocketmine.command.weather");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args) {
		if (!$this->testPermission($sender)) {
			return true;
		}

		if (count($args) < 1) {
			$sender->sendMessage("Usage: " . $this->usageMessage);
			return false;
		}

		if ($sender instanceof Player) {
			$wea = Weather::getWeatherFromString($args[0]);
			if (!isset($args[1])) {
				$duration = mt_rand(
					min(
						$sender->getServer()->weatherRandomDurationMin, 
						$sender->getServer()->weatherRandomDurationMax,
					),
					max(
						$sender->getServer()->weatherRandomDurationMin, 
						$sender->getServer()->weatherRandomDurationMax,
					)
				);
			} else {
				$duration = (int) $args[1];
			}
			if ($wea >= 0 && $wea <= 3) {
				$sender->getLevel()->getWeather()->setWeather($wea, $duration);
				$sender->sendMessage("Weather changed successfully in level " . $sender->getLevel()->getFolderName() . "!");
				return true;
//				if (WeatherManager::isRegistered($sender->getLevel())) {
//					$sender->getLevel()->getWeather()->setWeather($wea, $duration);
//					$sender->sendMessage("Weather changed successfully in level " . $sender->getLevel()->getFolderName() . "!");
//					return true;
//				} else {
//					$sender->sendMessage("level " . $sender->getLevel()->getFolderName() . " hasn't registered to WeatherManager.");
//					return false;
//				}
			} else {
				$sender->sendMessage(TextFormat::RED . "Invalid Weather.");
				return false;
			}
		}

		if (count($args) < 2) {
			$sender->sendMessage("Usage: " . $this->usageMessage);
			return false;
		}

		$level = $sender->getServer()->getLevelByName($args[0]);
		if (!$level instanceof Level) {
			$sender->sendMessage(TextFormat::RED . "Invalid Weather.");
			return false;
		}

		$wea = Weather::getWeatherFromString($args[1]);
		if (!isset($args[1])) {
			$duration = mt_rand(
				min(
					$sender->getServer()->weatherRandomDurationMin,
					$sender->getServer()->weatherRandomDurationMax,
				),
				max(
					$sender->getServer()->weatherRandomDurationMin,
					$sender->getServer()->weatherRandomDurationMax,
				)
			);
		} else {
			$duration = (int) $args[1];
		}
		if ($wea >= 0 && $wea <= 3) {
			$level->getWeather()->setWeather($wea, $duration);
			$sender->sendMessage("Weather changed successfully in level " . $args[0] . "!");
			return true;
//			if (WeatherManager::isRegistered($level)) {
//				$level->getWeather()->setWeather($wea, $duration);
//				$sender->sendMessage("Weather changed successfully in level " . $sender->getLevel()->getFolderName() . "!");
//				return true;
//			} else {
//				$sender->sendMessage("level " . $sender->getLevel()->getFolderName() . " hasn't registered to WeatherManager.");
//				return false;
//			}
		} else {
			$sender->sendMessage(TextFormat::RED . "Invalid Weather.");
			return false;
		}
	}

}
