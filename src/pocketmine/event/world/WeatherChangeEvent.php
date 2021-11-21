<?php

namespace pocketmine\event\world;

use pocketmine\event\Cancellable;
use pocketmine\world\World;
use pocketmine\world\weather\Weather;

class WeatherChangeEvent extends WorldEvent implements Cancellable {

	public static $handlerList = null;

	private $weather;
	private $duration;

	public function __construct(World $world, int $weather, int $duration) {
		parent::__construct($world);
		$this->weather = $weather;
		$this->duration = $duration;
	}

	public function getWeather() : int {
		return $this->weather;
	}

	public function setWeather(int $weather = Weather::SUNNY) {
		$this->weather = $weather;
	}

	public function getDuration() : int {
		return $this->duration;
	}

	public function setDuration(int $duration) {
		$this->duration = $duration;
	}

}
