<?php

namespace pocketmine\world\weather;

use pocketmine\world\World;

class WeatherManager {

	public static $registeredWorld = [];

	public static function registerWorld(World $World) {
		self::$registeredWorld[$world->getName()] = $world;
		return true;
	}

	public static function unregisterWorld(World $world) {
		if (isset(self::$registeredWorld[$world->getName()])) {
			unset(self::$registeredWorld[$world->getName()]);
			return true;
		}
		return false;
	}

	public static function updateWeather() {
		foreach (self::$registeredWorld as $world) {
			$world->getWeather()->calcWeather($world->getServer()->getTick());
		}
	}

	public static function isRegistered(World $world) {
		if (isset(self::$registeredWorld[$world->getName()])) {
			return true;
		}
		return false;
	}

}
