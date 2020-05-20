<?php

namespace pocketmine\level\generator\loot;

use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\block\MonsterSpawner;
use pocketmine\inventory\BaseInventory;
use pocketmine\item\Item;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\NBT;
use pocketmine\tile\Chest as ChestTile;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\utils\Random;

class Loot {

	private static $loot = [
		0 => array(
			"minCount" => 4,
			"maxCount" => 6,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 352,
			"data" => 0,
		),
		1 => array(
			"minCount" => 3,
			"maxCount" => 7,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 367,
			"data" => 0,
		),
		2 => array(
			"minCount" => 1,
			"maxCount" => 8,
			"minStacks" => 4,
			"maxStacks" => 4,
			"id" => 289,
			"data" => 0,
		),
		3 => array(
			"minCount" => 1,
			"maxCount" => 8,
			"minStacks" => 4,
			"maxStacks" => 4,
			"id" => 12,
			"data" => 0,
		),
		4 => array(
			"minCount" => 1,
			"maxCount" => 8,
			"minStacks" => 4,
			"maxStacks" => 4,
			"id" => 287,
			"data" => 0,
		),
		5 => array(
			"minCount" => 1,
			"maxCount" => 3,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 375,
			"data" => 0,
		),
		6 => array("minCount" => 1,
			"maxCount" => 1,
			"maxStacks" => 1,
			"minStacks" => 1,
			"id" => 403,
			"data" => 0,
		),
		7 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 329,
			"data" => 0,
		),
		8 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 1,
			"maxStacks" => 1,
			"id" => 322,
			"data" => 0,
		),
		9 => array(
			"minCount" => 2,
			"maxCount" => 7,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 266,
			"data" => 0,
		),
		10 => array(
			"minCount" => 1,
			"maxCount" => 5,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 265,
			"data" => 0,
		),
		11 => array(
			"minCount" => 1,
			"maxCount" => 3,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 388,
			"data" => 0,
		),
		12 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 417,
			"data" => 0,
		),
		13 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 418,
			"data" => 0,
		),
		14 => array(
			"minCount" => 1,
			"maxCount" => 3,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 264,
			"data" => 0,
		),
		15 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 419,
			"data" => 0,
		),
		16 => array(
			"minCount" => 1,
			"maxCount" => 1,
			"minStacks" => 2,
			"maxStacks" => 4,
			"id" => 466,
			"data" => 0
		),
	];

	public static function placeAll(ChunkManager $level) {
		foreach (self::$loot as $index => $data) {
			self::$loot[$index]["item"] = Item::get($data["id"], $data["data"]);
		}
		$stream = file_get_contents(\pocketmine\RESOURCE_PATH . "processingLoot.txt");
		$data = explode(PHP_EOL, trim($stream));
		while (is_integer($k = array_search("", $data))) {
			unset($data[$k]);
		}
		$c = 0;
		foreach ($data as $pos) {
			$c++;
			$xyz = explode(":", $pos);
			if ($xyz[3] != "c") {
			continue;
			}
			$v = new Vector3($xyz[0], $xyz[1], $xyz[2]);
			$level->setBlockIdAt($xyz[0], $xyz[1], $xyz[2], Block::AIR);
			if ($level->getBlockIdAt($xyz[0], $xyz[1] - 1, $xyz[2]) != Block::AIR) {
				$level->setBlock($v, new Chest());
			}
			$nbt = new Compound("", [
				new Enum("Items", []),
				new StringTag("id", Tile::CHEST),
				new IntTag("x", $v->x),
				new IntTag("y", $v->y),
				new IntTag("z", $v->z),
            ]);
			$nbt->Items->setTagType(NBT::TAG_Compound);
			Tile::createTile("Chest", $level->getChunk($v->x >> 4, $v->z >> 4), $nbt);
			echo "Created tile {$c}" . PHP_EOL;
		}
		$c = 0;
		foreach ($data as $pos) {
			$c++;
			$xyz = explode(":", $pos);
			$v = new Vector3($xyz[0], $xyz[1], $xyz[2]);
			$tile = $level->getTile($v);
			if ($tile != null && $tile instanceof ChestTile) {
				self::fillChest($tile);
			}
			echo "Filled chest {$c}" . PHP_EOL;
		}
		$c = 0;
		foreach ($data as $pos) {
			$c++;
			$xyz = explode(":", $pos);
			if ($xyz[3] != "s") {
				continue;
			}
			$v = new Vector3($xyz[0], $xyz[1], $xyz[2]);
			$level->setBlock($v, new MonsterSpawner());
			$nbt = new Compound("", [
				new IntTag("x", $v->x),
				new IntTag("y", $v->y),
				new IntTag("z", $v->z),
				new IntTag("EntityId", 32),
				new IntTag("SpawnCount", 4),
				new IntTag("SpawnRange", 4),
				new IntTag("MinSpawnDelay", 200),
				new IntTag("MaxSpawnDelay", 799),
				new IntTag("Delay", mt_rand(200, 799)),
			]);
			$tile = Tile::createTile("MobSpawner", $level->getChunk($v->x >> 4, $v->z >> 4), $nbt);
			echo "Placed spawner {$c}" . PHP_EOL;
		}
		echo "Completed!\n";
	}

	public static function fillChest(Tile &$tile) {
		$inv = $tile->getInventory();
		$inv->clearAll();
		$slots = $inv->getSize();
		$content = self::getRandomItems();
		foreach ($content as $item) {
			$index = mt_rand(1, $slots) - 1;
			$inv->setItem($index, $item);
		}
	}

	public static function getRandomItems() {
		$items = [];
		$rand = static::$loot;
		for ($i = 0; $i < mt_rand(8, 12) + 1; $i++) {
			$data = $rand[mt_rand(0, 16)];
			for ($j = $data["minStacks"]; $j <= mt_rand($data["minStacks"], $data["maxStacks"]); $j++) {
				$item = $data["item"];
				$item->setCount(mt_rand($data["minCount"], $data["maxCount"]));
				$items[] = $item;
			}
		}
		return $items;
	}

}
