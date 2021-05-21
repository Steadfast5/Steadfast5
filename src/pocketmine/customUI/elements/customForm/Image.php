<?php

namespace pocketmine\customUI\elements\customForm;

use pocketmine\customUI\elements\UIElement;
use pocketmine\Player;

class Image extends UIElement {

	public $texture;
	public $width;
	public $height;

	public function __construct($texture, $width = 0, $height = 0) {
		$this->texture = $texture;
		$this->width = $width;
		$this->height = $height;
	}

	final public function toJSON() {
		return [
			"text" => "sign",
			"type" => "image",
			"texture" => $this->texture,
			"size" => [
				$this->width,
				$this->height,
			],
		];
	}

	public function handle($response, Player $player) {
		return null;
	}

}
