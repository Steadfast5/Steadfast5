<?php

namespace pocketmine\world\generator\populator;

use pocketmine\block\Block;
use pocketmine\world\ChunkManager;
use pocketmine\utils\Random;

class WaterPit extends Populator {

	/** @var ChunkManager */
	private $world;
	private $randomAmount;
	private $baseAmount;

	public function setRandomAmount($amount) {
		$this->randomAmount = $amount;
	}

	public function setBaseAmount($amount) {
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $world, $chunkX, $chunkZ, Random $random) {
		$this->world = $world;
		$amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
		for ($i = 0; $i < $amount; ++$i) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);

			if ($y !== -1 && $this->canWaterPitStay($x, $y, $z)) {
				$this->world->setBlockIdAt($x, $y, $z, Block::STILL_WATER);
				$this->world->setBlockDataAt($x, $y, $z , 8);
			}
		}
	}

	private function canWaterPitStay($x, $y, $z) {
		$b = $this->world->getBlockIdAt($x, $y, $z);
		return ($b === Block::AIR || $b === Block::GRASS) && $this->world->getBlockIdAt($x, $y, $z) === Block::DIRT;
	}

	private function getHighestWorkableBlock($x, $z) {
		for ($y = 61; $y >= 0; --$y) {
			$b = $this->world->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR && $b !== Block::LEAVES && $b !== Block::LEAVES2 && $b !== Block::SNOW_LAYER) {
				break;
			}
		}
		return $y === 0 ? -1 : ++$y;
	}

}
