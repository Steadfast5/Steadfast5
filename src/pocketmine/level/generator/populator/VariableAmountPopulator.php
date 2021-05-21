<?php

namespace pocketmine\level\generator\populator;

use pocketmine\utils\Random;

abstract class VariableAmountPopulator extends Populator {

	protected $baseAmount;
	protected $randomAmount;
	protected $odd;

	public function __construct(int $baseAmount = 0, int $randomAmount = 0, int $odd = 0) {
		$this->baseAmount = $baseAmount;
		$this->randomAmount = $randomAmount;
		$this->odd = $odd;
	}

	public function setOdd(int $odd) {
		$this->odd = $odd;
	}

	public function checkOdd(Random $random) {
		if ($random->nextRange(0, $this->odd) == 0) {
			return true;
		}
		return false;
	}

	public function getAmount(Random $random) {
		return $this->baseAmount + $random->nextRange(0, $this->randomAmount + 1);
	}

	public final function setBaseAmount(int $baseAmount) {
		$this->baseAmount = $baseAmount;
	}

	public final function setRandomAmount(int $randomAmount) {
		$this->randomAmount = $randomAmount;
	}

	public function getBaseAmount() {
		return $this->baseAmount;
	}

	public function getRandomAmount() {
		return $this->randomAmount;
	}

}
