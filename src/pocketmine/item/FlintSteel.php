<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\Solid;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use function assert;

class FlintSteel extends Tool {

	/** @var Vector3 */
	private $temporalVector = null;

	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::FLINT_STEEL, $meta, $count, "Flint and Steel");
		if ($this->temporalVector === null) {
			$this->temporalVector = new Vector3(0, 0, 0);
		}
	}

	public function canBeActivated(){
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$tx = $target->getX();
		$ty = $target->getY();
		$tz = $target->getZ();
		$clickVector = new Vector3($tx, $ty, $tz);
		if ($target->getId() === self::AIR) {
			assert($level !== null);
			$level->setBlock($target, new Fire(), true);
			$pos = $target->add(0.5, 0.5, 0.5);
			$soundId = LevelSoundEventPacket::SOUND_IGNITE;
			$pk = new LevelSoundEventPacket();
			$pk->sound = $soundId;
			$pk->pitch = $pitch;
			$pitch = 1;
			$extraData = -1;
			$unknown = false;
			$disableRelativeVolume = false;
			$pk->extraData = $extraData;
			$pk->unknownBool = $unknown;
			$pk->disableRelativeVolume = $disableRelativeVolume;
			list($pk->x, $pk->y, $pk->z) = [$pos->x, $pos->y, $pos->z];
			$level->addChunkPacket($pos->x >> 4, $pos->z >> 4, $pk);
			return true;
		}

		return false;
	}

	public function getMaxDurability() : int{
		return 65;
	}

}
