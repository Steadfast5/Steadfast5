<?php

namespace pocketmine\block;

class WetSponge extends Solid {

	protected $id = self::WET_SPONGE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getResistance(){
		return 3;
	}

	public function getHardness(){
		return 0.6;
	}

	public function getName(){
		return "Wet Sponge";
	}

}
