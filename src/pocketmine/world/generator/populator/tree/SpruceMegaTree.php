<?php

namespace pocketmine\level\generator\populator\tree;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\generator\object\tree\ObjectBigSpruceTree;
use pocketmine\level\generator\populator\Populator;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SpruceMegaTree extends Populator {

	private $level;
	private $randomAmount;
	private $baseAmount;
	private $type;

	public function __construct(int $type = Sapling::SPRUCE) {
		$this->type = $type;
	}

	public function setRandomAmount(int $randomAmount) {
		$this->randomAmount = $randomAmount;
	}

	public function setBaseAmount(int $baseAmount) {
		$this->baseAmount = $baseAmount;
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $random->nextBoundedInt($this->randomAmount + 1) + $this->baseAmount;
		$v = new Vector3();
        $chunk = $level->getChunk($chunkX, $chunkZ);
		for ($i = 0; $i < $amount; ++$i) {
			$x = $this->randomRange($random, $chunkX << 4, ($chunkX << 4) + 15);
			$z = $this->randomRange($random, $chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($level, $x, $z, $chunk);
			if ($y == -1) {
				continue;
			}
			$obj = new ObjectBigSpruceTree(floatval(1 / 4), 5);
			$obj->placeObject($this->level, (int)($v->x = $x), (int)($v->y = $y), (int)($v->z = $z), $random);
		}
	}

	private function randomRange(Random $random, int $start, int $end) {
		return $start + ($random->nextInt() % ($end + 1 - $start));
	}

	protected function getHighestWorkableBlock(ChunkManager $level, int $x, int $z, FullChunk $chunk) {
		for ($y = 255; $y > 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b == Block::DIRT || $b == Block::GRASS) {
				break;
			} elseif ($b != Block::AIR && $b != Block::SNOW_LAYER) {
				return -1;
			}
		}
		return ++$y;
	}

}
