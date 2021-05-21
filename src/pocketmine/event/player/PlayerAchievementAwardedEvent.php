<?php

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerAchievementAwardedEvent extends PlayerEvent implements Cancellable {

	public static $handlerList = null;

	protected $achievement;

	public function __construct(Player $player, $achievementId) {
		$this->player = $player;
		$this->achievement = $achievementId;
	}

	public function getAchievement() {
		return $this->achievement;
	}

}
