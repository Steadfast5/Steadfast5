<?php

namespace pocketmine\entity;

/**
 * Only for Slimes and Magma Cubes
 */

class JumpingEntity extends BaseEntity {
	
	protected function checkTarget() {

	}

	public function updateMove() {
		$this->jump();
		return null;
	}

}
