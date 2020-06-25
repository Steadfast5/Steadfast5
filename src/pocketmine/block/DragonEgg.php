<?php

namespace pocketmine\block;

use pocketmine\event\block\BlockTeleportEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\sound\GenericSound;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\Player;

class DragonEgg extends Fallable {

	protected $id = self::DRAGON_EGG;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() {
		return 'Dragon Egg';
	}

	public function getLightLevel() {
		return 1;
	}

	public function getHardness() {
		return 4.5;
	}

	public function isBreakable(Item $item) {
		return false;
	}

	public function canBeActivated() {
		return true;
	}

	public function onActivate(Item $item, Player $player = null) {
		$safe = false;
		while (!$safe) {
			$level = $this->getLevel();
			$x = $this->getX() + mt_rand(-15, 15);
			$y = $this->getY() + mt_rand(-7, 7);
			$z = $this->getZ() + mt_rand(-15, 15);
			if ($level->getBlockIdAt($x, $y, $z) == Block::AIR && $y < $level->maxY) {
				$safe = true;
				break;
			}
		}

		$level->setBlock($this, new Air(), true, true);

		$oldPos = clone $this;
		$pos = new Position($x, $y, $z, $level);
		$newPos = clone $pos;

		$ev = new BlockTeleportEvent($this, $oldPos, $newPos);
		$ev->call();
		if (!$ev->isCancelled()) {
			$level->setBlock($pos, $this, true, true);
			$posDistance = new Position(
				$newPos->x - $oldPos->x,
				$newPos->y - $oldPos->y,
				$newPos->z - $oldPos->z,
				$this->getLevel()
			);
			$intDistance = $oldPos->distance($newPos);
			for ($i = 0; $i <= $intDistance; $i++) {
				$progress = $i / $intDistance;
				$this->getLevel()->addSound(new GenericSound(
					new Position(
						$oldPos->x + $posDistance->x * $progress,
						1.62 + $oldPos->y + $posDistance->y * $progress,
						$oldPos->z + $posDistance->z * $progress,
						$this->getLevel()
					),
					LevelEventPacket::EVENT_PARTICLE_PORTAL_1
				));
			}
		}
		return $safe;
	}

}
