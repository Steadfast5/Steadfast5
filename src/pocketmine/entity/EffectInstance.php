<?php

namespace pocketmine\entity;

use pocketmine\utils\Color;
use function max;
use const INT32_MAX;

class EffectInstance {

	private $effectType;
	private $duration;
	private $amplifier;
	private $visible;
	private $ambient;
	private $color;

	public function __construct(Effect $effectType, $duration = null, $amplifier = 0, $visible = true, $ambient = false, $overrideColor = null) {
		$this->effectType = $effectType;
		$this->setDuration($duration ?? $effectType->getDefaultDuration());
		$this->amplifier = $amplifier;
		$this->visible = $visible;
		$this->ambient = $ambient;
		$this->color = $overrideColor ?? $effectType->getColor();
	}

	public function getId() {
		return $this->effectType->getId();
	}

	public function getType() {
		return $this->effectType;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function setDuration(int $duration) {
		if ($duration < 0 or $duration > INT32_MAX) {
			throw new \InvalidArgumentException("Effect duration must be in range 0 - " . INT32_MAX . ", got $duration");
		}
		$this->duration = $duration;
		return $this;
	}

	public function decreaseDuration(int $ticks) : EffectInstance{
		$this->duration = max(0, $this->duration - $ticks);
		return $this;
	}

	public function hasExpired() {
		return $this->duration <= 0;
	}

	public function getAmplifier() {
		return $this->amplifier;
	}

	public function getEffectLevel() {
		return $this->amplifier + 1;
	}

	public function setAmplifier(int $amplifier) {
		$this->amplifier = $amplifier;
		return $this;
	}

	public function isVisible() {
		return $this->visible;
	}

	public function setVisible(bool $visible = true) {
		$this->visible = $visible;
		return $this;
	}

	public function isAmbient() {
		return $this->ambient;
	}

	public function setAmbient(bool $ambient = true) {
		$this->ambient = $ambient;
		return $this;
	}

	public function getColor() {
		return clone $this->color;
	}

	public function setColor(Color $color) {
		$this->color = clone $color;
		return $this;
	}

	public function resetColor() {
		$this->color = $this->effectType->getColor();
		return $this;
	}

}
