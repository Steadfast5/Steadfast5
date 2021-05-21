<?php

namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\projectile\Projectile;
use pocketmine\math\RayTraceResult;

class ProjectileHitBlockEvent extends ProjectileHitEvent {

	/** @var Block */
	private $blockHit;

	public function __construct(Projectile $entity, RayTraceResult $rayTraceResult, Block $blockHit) {
		parent::__construct($entity, $rayTraceResult);
		$this->blockHit = $blockHit;
	}

	/**
	 * @return Block
	 */
	public function getBlockHit() {
		return $this->blockHit;
	}

}
