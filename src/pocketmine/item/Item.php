<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

/**
 * All the Item classes
 */
namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Flower;
use pocketmine\entity\Entity;
use pocketmine\entity\Squid;
use pocketmine\entity\Villager;
//use pocketmine\entity\Zombie;
use pocketmine\inventory\Fuel;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\level\Level;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\NBT;

class Item implements ItemIds {

    private static $cachedParser = null;
    private static $itemBlockClass = ItemBlock::class;

    /**
     * @param $tag
     * @return Compound
     */
    private static function parseCompound($tag){
        if(self::$cachedParser === null){
            self::$cachedParser = new NBT(NBT::LITTLE_ENDIAN);
        }

        self::$cachedParser->read($tag);
        return self::$cachedParser->getData();
    }

    /**
     * @param Compound $tag
     * @return string
     */
    private static function writeCompound(Compound $tag){
        if(self::$cachedParser === null){
            self::$cachedParser = new NBT(NBT::LITTLE_ENDIAN);
        }

        self::$cachedParser->setData($tag);
        return self::$cachedParser->write(true);
    }

	protected static $names = [
		0 => "Air",
		1 => "Stone",
		2 => "Grass",
		3 => "Dirt",
		4 => "Cobblestone",
		5 => "Plank",
		6 => "Sapling",
		7 => "Bedrock",
		8 => "Water",
		9 => "Still Water",
		10 => "Lava",
		11 => "Still Lava",
		12 => "Sand",
		13 => "Gravel",
		14 => "Gold Ore",
		15 => "Iron Ore",
		16 => "Coal Ore",
		17 => "Wood",
		18 => "Leaves",
		19 => "Sponge",
		20 => "Glass",
		21 => "Lapis Ore",
		22 => "Lapis Block",
		24 => "Sandstone",
		25 => "Note block",
		26 => "Bed",
		30 => "Cobweb",
		31 => "Tall Grass",
		32 => "Bush",
		35 => "Wool",
		37 => "Dandelion",
		38 => "Red Flower",
		39 => "Brown Mushroom",
		40 => "Red Mushroom",
		41 => "Gold Block",
		42 => "Iron Block",
		43 => "Double Slab",
		44 => "Slab",
		45 => "Bricks",
		46 => "TNT",
		47 => "Bookshelf",
		48 => "Moss Stone",
		49 => "Obsidian",
		50 => "Torch",
		51 => "Fire",
		52 => "Monster Spawner",
		53 => "Wooden Stairs",
		54 => "Chest",
		56 => "Diamond Ore",
		57 => "Diamond Block",
		58 => "Crafting Table",
		59 => "Wheat Block",
		60 => "Farmland",
		61 => "Furnace",
		62 => "Burning Furnace",
		63 => "Sign Post",
		64 => "Door",
		65 => "Ladder",
		66 => "Rail",
		67 => "Cobble Stairs",
		68 => "Wall Sign",
		71 => "Iron Door",
		73 => "Redstone Ore",
		74 => "Glowing Redstone Ore",
		75 => "Redstone Torch",
		76 => "Glowing Redstone Torch",
		78 => "Snow",
		79 => "Ice",
		80 => "Snow Block",
		81 => "Cactus",
		82 => "Clay Block",
		83 => "Sugarcane Block",
		85 => "Fence",
		86 => "Pumpkin Block",
		87 => "Netherrack",
		88 => "Soul Sand",
		89 => "Glowstone",
		90 => "Portal",
		91 => "Jack-O'-Lantern",
		92 => "Cake Block",
		96 => "Trapdoor",
		98 => "Stone Bricks",
		99 => "Brown Mushroom Block",
		100 => "Red Mushroom Block",
		101 => "Iron Bar",
		102 => "Glass Panel",
		103 => "Melon BLock",
		104 => "Pumpkin Stem",
		106 => "Vine",
		107 => "Fence Gate",
		108 => "Brick Stairs",
		109 => "Stone Brick Stairs",
		110 => "Mycelium",
		111 => "Water Lily",
		112 => "Nether Brick",
		113 => "Nether Brick Fence",
		114 => "Nether Brick Stairs",
		116 => "Enchantment Table",
		117 => "Brewing Stand",
		118 => "Cauldron Block",
		120 => "End Portal",
		121 => "End Stone",
		self::DRAGON_EGG => 'Dragon Egg',
		123 => "Redstone Lamp",
		124 => "Redstone Lamp Active",
		125 => "Dropper",
		126 => "Activator Rail",
		127 => "Cocoa",
		128 => "Sendstone Stairs",
		129 => "Emerald Ore",
		self::ENDER_CHEST => 'Ender Chest',
		131 => "Tripwire Hook",
		132 => "Tripwire",
		133 => "Emerald Block",
		134 => "Spruce Wood Stairs",
		135 => "Birch Wood Stairs",
		136 => "Jungle Wood Stairs",
		138 => "Beacon",
		139 => "Cobblestone Wall",
		140 => "Flower Pot",
		141 => "Carrot Block",
		142 => "Potato Block",
		143 => "Wooden Button",
		144 => "Mob Head",
		145 => "Anvil",
		146 => "Trapped Chest",
		147 => "Weighted Pressure Plate Light",
		148 => "Weighted Pressure Plate Heavy",
		149 => "Redstone Comparator",
		150 => "Redstone Comparator Powered",
		151 => "Daylight Sensor",
		152 => "Redstone Block",
		153 => "Nether Quartz Ore",
		154 => "Hopper",
		155 => "Quartz Block",
		156 => "Quartz Stairs",
		157 => "Double Wood Slab",
		158 => "Wooden Slab",
		159 => "Stained Clay",
		self::STAINED_GLASS_PANE => 'Stained Glass Pane',
		161 => "Leaves2",
		162 => "Wood2",
		163 => "Acacia Wood Stairs",
		164 => "Dark Oak Wood Stairs",
		165 => "Slime Block",
		167 => "Iron Trapdoor",
		170 => "Hay Bale",
		171 => "Carpet",
		172 => "Hardened CLay",
		173 => "Coal BLock",
		175 => "Double Plant",
		178 => "Inverted Daylight Sensor",
		179 => "Red Sandstone",
		180 => "Red Sandstone Stairs",
		181 => "Double Red Sandstone Slab",
		182 => "Red Sandstone Slab",
		183 => "Fence Gate Spruce",
		184 => "Fence Gate Birch",
		185 => "Fence Gate Jungle",
		186 => "Fence Gate Dark Oak",
		187 => "Fence Gate Acacia",
		193 => "Wood Door Block",
		194 => "Birch Door",
		195 => "Jungle Door",
		196 => "Acacia Door",
		197 => "Dark Oak Door",
		198 => "Grass Path",
		self::CHORUS_FLOWER => 'Chorus Flower',
		self::PURPUR_BLOCK => 'Purpur Block',
		self::END_BRICKS => 'End Brick',
		self::END_ROD => 'End Rod',
		self::CHORUS_PLANT => 'Chorus Plant',
		self::STAINED_GLASS => 'Stained Glass',
		213 => "Magma",
		214 => "Nether Wart Block Block",
		243 => "Podzol",
		244 => "Beetroot Block",
		245 => "Stonecutter",
		246 => "Glowing Obsidian",
		247 => "Nether Reactor",
		256 => "Iron Shovel",
		257 => "Iron Pickaxe",
		258 => "Iron Axe",
		259 => "Flint and Steel",
		260 => "Apple",
		261 => "Bow",
		262 => "Arrow",
		263 => "Coal",
		264 => "Diamond",
		265 => "Iron Ingot",
		266 => "Gold Ingot",
		267 => "Iron Sword",
		268 => "Wooden Sword",
		269 => "Wooden Shovel",
		270 => "Wooden Pickaxe",
		271 => "Wooden Axe",
		272 => "Stone Sword",
		273 => "Stone Shovel",
		274 => "Stone Pickaxe",
		275 => "Stone Axe",
		276 => "Diamond Sword",
		277 => "Diamond Shovel",
		278 => "Diamond Pickaxe",
		279 => "Diamond Axe",
		280 => "Stick",
		281 => "Bowl",
		282 => "Mushroom Stew",
		283 => "Gold Sword",
		284 => "Gold Shovel",
		285 => "Gold Pickaxe",
		286 => "Gold Axe",
		287 => "String",
		288 => "Feather",
		289 => "Gunpowder",
		290 => "Wooden Hoe",
		291 => "Stone Hoe",
		292 => "Iron Hoe",
		293 => "Diamond Hoe",
		294 => "Gold Hoe",
		295 => "Wheat Seed",
		296 => "Wheat",
		297 => "Bread",
		298 => "Leather Cap",
		299 => "Leather Tunic",
		300 => "Leather Pants",
		301 => "Leather Boots",
		302 => "Chain Helmet",
		303 => "Chain Chestplate",
		304 => "Chain Leggins",
		305 => "Chain Boots",
		306 => "Iron Helmet",
		307 => "Iron Chestplate",
		308 => "Iron Leggins",
		309 => "Iron Boots",
		310 => "Diamond Helmet",
		311 => "Diamond Chestplate",
		312 => "Diamond Leggins",
		313 => "Diamond Boots",
		314 => "Gold Helmet",
		315 => "Gold Chestplate",
		316 => "Gold Leggins",
		317 => "Gold Boots",
		318 => "Flint",
		319 => "Raw Porkchop",
		320 => "Cooked Porkchop",
		321 => "Painting",
		322 => "Golden Apple",
		323 => "Sign",
		324 => "Wooden Door",
		325 => "Bucket",
		328 => "Minecart",
		330 => "Iron Door",
		331 => "Redstone",
		332 => "Snowball",
		334 => "Leather",
		336 => "Bricks",
		337 => "Clay",
		338 => "Sugarcane",
		339 => "Paper",
		340 => "Book",
		341 => "Slimeball",
		344 => "Egg",
		345 => "Compass",
		346 => "Compass",
		347 => "Clock",
		348 => "Glowstone Dust",
		349 => "Raw Fish",
		350 => "Cooked Fish",
		351 => "Dye",
		352 => "Bone",
		353 => "Sugar",
		354 => "Cake",
		355 => "Bed",
		357 => "Cookie",
		359 => "Shears",
		360 => "Melon",
		361 => "Pumpkin Seed",
		362 => "Melon Seed",
		363 => "Raw Beef",
		364 => "Steak",
		365 => "Raw Chicken",
		366 => "Cooked Chicken",
		369 => "Blaze Rod",
		371 => "Gold Nugget",
		373 => "Potion",
		377 => "Blaze powder",
		378 => "Magma Cream",
		383 => "Spawn Egg",
		self::WRITABLE_BOOK => "Book & Quill",
		self::WRITTEN_BOOK => "Written Book",
		388 => "Emerald",
		390 => "Flower Pot",
		391 => "Carrot",
		392 => "Potato",
		393 => "Baked Potato",
		394 => "Poisonous Potato",
		395 => "Empty Map",
		396 => "Golden Carrot",
		397 => "Mob Head",
		398 => "Carrot on a Stick",
		400 => "Pumpkin Pie",
		403 => "Enchanted Book",
		404 => "Redstone Comparator",
		405 => "Nether Bricks",
		406 => "Quartz",
		407 => "Minecart with TNT",
		408 => "Minecart with Hopper",
		410 => "Hopper",
		411 => "Raw Rabbit",
		412 => "Cooked Rabbit",
		414 => "Rabbit's Foot",
		415 => "Rabbit Hide",
		416 => "Leather Horse Armor",
 		417 => "Iron Horse Armor",
		418 => "Golden Horse Armor",
		419 => "Diamond Horse Armor",
		420 => "Lead",
		421 => "Name Tag",
		self::PRISMARINE_CRYSTAL => "Prismarine Crystal",
		423 => "Raw Mutton",
		424 => "Cooked Mutton",
		427 => "Spruce Door",
		428 => "Birch Door",
		429 => "Jungle Door",
		430 => "Acacia Door",
		431 => "Dark Oak Door",
		431 => "Chorus Fruit",
		438 => "Splash Potion",
		450 => "Totem of Undying",
		457 => "Beetroot",
		458 => "Beetroot Seed",
		459 => "Beetroot Soup",
		460 => "Raw Salmon",
		461 => "Clownfish",
		462 => "Pufferfish",
		463 => "Cooked Salmon",
		466 => "Enchanted Golden Apple",
		498 => "Camera",
	];

    /** @var \SplFixedArray */
    public static $list = null;

    public static $food = null;

    protected $block;

    protected $id;

    protected $meta;

    private $tags = "";

    private $cachedNBT = null;

    public $count;

    protected $durability = 0;

    protected $name;

    protected $obtainTime = 0;

    protected $canPlaceOnBlocks = [];

    protected $canDestroyBlocks = [];

    public function canBeActivated(){
        return false;
    }

    public static function init(){
        if(self::$list === null){
            self::$list = new \SplFixedArray(65536);
            self::$list[self::GOLDEN_APPLE] = GoldenApple::class;
            self::$list[self::ENCHANTED_GOLDEN_APPLE] = EnchantedGoldenApple::class;
            self::$list[self::SUGARCANE] = Sugarcane::class;
            self::$list[self::WHEAT_SEEDS] = WheatSeeds::class;
            self::$list[self::PUMPKIN_SEEDS] = PumpkinSeeds::class;
            self::$list[self::MELON_SEEDS] = MelonSeeds::class;
            self::$list[self::MUSHROOM_STEW] = MushroomStew::class;
            self::$list[self::BEETROOT_SOUP] = BeetrootSoup::class;
            self::$list[self::CARROT] = Carrot::class;
            self::$list[self::POTATO] = Potato::class;
            self::$list[self::BEETROOT_SEEDS] = BeetrootSeeds::class;
            self::$list[self::SIGN] = Sign::class;
            self::$list[self::WOODEN_DOOR] = WoodenDoor::class;
            self::$list[self::MINECART] = Minecart::class;
            self::$list[self::BOAT] = Boat::class;
            self::$list[self::FISHING_ROD] = FishingRod::class;
            self::$list[self::BUCKET] = Bucket::class;
            self::$list[self::IRON_DOOR] = IronDoor::class;
            self::$list[self::CAKE] = Cake::class;
            self::$list[self::BED] = Bed::class;
            self::$list[self::PAINTING] = Painting::class;
            self::$list[self::COAL] = Coal::class;
            self::$list[self::APPLE] = Apple::class;
            self::$list[self::SPAWN_EGG] = SpawnEgg::class;
            self::$list[self::DIAMOND] = Diamond::class;
            self::$list[self::STICK] = Stick::class;
            self::$list[self::SNOWBALL] = Snowball::class;
            self::$list[self::EGG] = Egg::class;
            self::$list[self::BOWL] = Bowl::class;
            self::$list[self::FEATHER] = Feather::class;
            self::$list[self::BRICK] = Brick::class;
            self::$list[self::LEATHER_CAP] = LeatherCap::class;
            self::$list[self::ITEM_FRAME] = ItemFrame::class;
            self::$list[self::LEATHER_TUNIC] = LeatherTunic::class;
            self::$list[self::LEATHER_PANTS] = LeatherPants::class;
            self::$list[self::LEATHER_BOOTS] = LeatherBoots::class;
            self::$list[self::CHAIN_HELMET] = ChainHelmet::class;
            self::$list[self::CHAIN_CHESTPLATE] = ChainChestplate::class;
            self::$list[self::CHAIN_LEGGINGS] = ChainLeggings::class;
            self::$list[self::CHAIN_BOOTS] = ChainBoots::class;
            self::$list[self::IRON_HELMET] = IronHelmet::class;
            self::$list[self::IRON_CHESTPLATE] = IronChestplate::class;
            self::$list[self::IRON_LEGGINGS] = IronLeggings::class;
            self::$list[self::IRON_BOOTS] = IronBoots::class;
            self::$list[self::GOLD_HELMET] = GoldHelmet::class;
            self::$list[self::GOLD_CHESTPLATE] = GoldChestplate::class;
            self::$list[self::GOLD_LEGGINGS] = GoldLeggings::class;
            self::$list[self::GOLD_BOOTS] = GoldBoots::class;
            self::$list[self::DIAMOND_HELMET] = DiamondHelmet::class;
            self::$list[self::DIAMOND_CHESTPLATE] = DiamondChestplate::class;
            self::$list[self::DIAMOND_LEGGINGS] = DiamondLeggings::class;
            self::$list[self::DIAMOND_BOOTS] = DiamondBoots::class;
            self::$list[self::IRON_SWORD] = IronSword::class;
            self::$list[self::IRON_INGOT] = IronIngot::class;
            self::$list[self::GOLD_INGOT] = GoldIngot::class;
            self::$list[self::IRON_SHOVEL] = IronShovel::class;
            self::$list[self::IRON_PICKAXE] = IronPickaxe::class;
            self::$list[self::IRON_AXE] = IronAxe::class;
            self::$list[self::IRON_HOE] = IronHoe::class;
            self::$list[self::DIAMOND_SWORD] = DiamondSword::class;
            self::$list[self::DIAMOND_SHOVEL] = DiamondShovel::class;
            self::$list[self::DIAMOND_PICKAXE] = DiamondPickaxe::class;
            self::$list[self::DIAMOND_AXE] = DiamondAxe::class;
            self::$list[self::DIAMOND_HOE] = DiamondHoe::class;
            self::$list[self::GOLD_SWORD] = GoldSword::class;
            self::$list[self::GOLD_SHOVEL] = GoldShovel::class;
            self::$list[self::GOLD_PICKAXE] = GoldPickaxe::class;
            self::$list[self::GOLD_AXE] = GoldAxe::class;
            self::$list[self::GOLD_HOE] = GoldHoe::class;
            self::$list[self::STONE_SWORD] = StoneSword::class;
            self::$list[self::STONE_SHOVEL] = StoneShovel::class;
            self::$list[self::STONE_PICKAXE] = StonePickaxe::class;
            self::$list[self::STONE_AXE] = StoneAxe::class;
            self::$list[self::STONE_HOE] = StoneHoe::class;
            self::$list[self::WOODEN_SWORD] = WoodenSword::class;
            self::$list[self::WOODEN_SHOVEL] = WoodenShovel::class;
            self::$list[self::WOODEN_PICKAXE] = WoodenPickaxe::class;
            self::$list[self::WOODEN_AXE] = WoodenAxe::class;
            self::$list[self::WOODEN_HOE] = WoodenHoe::class;
            self::$list[self::FLINT_STEEL] = FlintSteel::class;
            self::$list[self::SHEARS] = Shears::class;
            self::$list[self::BOW] = Bow::class;
            self::$list[self::RAW_FISH] = Fish::class;
            self::$list[self::COOKED_FISH] = CookedFish::class;
            self::$list[self::MOB_HEAD] = MobHead::class;
            self::$list[self::BLAZE_POWDER] = BlazePowder::class;
            self::$list[self::FLOWER_POT] = FlowerPot::class;
            self::$list[self::ELYTRA] = Elytra::class;
            self::$list[self::PRISMARINE_CRYSTAL] = PrismarineCrystal::class;
            self::$list[self::POTION] = Potion::class;
            self::$list[self::REDSTONE_REPEATER] = Repeater::class;
            self::$list[self::BOTTLE_ENCHANTING] = BottleOEnchanting::class;
            self::$list[self::WRITABLE_BOOK] = WritableBook::class;

            self::$list[self::SPRUCE_DOOR] = SpruceDoor::class;
            self::$list[self::BIRCH_DOOR] = BirchDoor::class;
            self::$list[self::JUNGLE_DOOR] = JungleDoor::class;
            self::$list[self::ACACIA_DOOR] = AcaciaDoor::class;
            self::$list[self::DARK_OAK_DOOR] = DarkOakDoor::class;
            self::$list[self::SPLASH_POTION] = SplashPotion::class;

            // update for 1.0
            self::$list[self::CHORUS_FRUIT] = ChorusFruit::class;
            self::$list[self::REDSTONE_DUST] = Redstone::class;
            self::$list[self::TOTEM_OF_UNDYING] = TotemOfUndying::class;
        }

        self::initCreativeItems();
        self::initFood();
    }

    public static function registerItem($id, $class) {
        if (isset(self::$list[$id]) && self::$list[$id] == $class) {
            return;
        }
        self::$list[$id] = $class;
        foreach (self::$creative as $index => $itemData) {
            $item = $itemData['item'];
            if ($item->getId() == $id) {
                self::$creative[$index] = ['item' => Item::get($id, $item->getDamage()), 'group' => self::CREATIVE_GROUP_NONE];
            }
        }
    }

	const CREATIVE_GROUP_NONE = 0xffffffff;
	const CREATIVE_GROUP_PLANKS = 0;
	const CREATIVE_GROUP_WALLS = 1;
	const CREATIVE_GROUP_FENCE = 2;
	const CREATIVE_GROUP_FENCEGATE = 3;
	const CREATIVE_GROUP_STAIRS = 4;
	const CREATIVE_GROUP_DOOR = 5;
	const CREATIVE_GROUP_TRAPDOOR = 6;
	const CREATIVE_GROUP_GLASS = 7;
	const CREATIVE_GROUP_GLASSPANE = 8;
	const CREATIVE_GROUP_SLAB = 9;
	const CREATIVE_GROUP_STONEBRICK = 10;
	const CREATIVE_GROUP_SANDSTONE = 11;
	const CREATIVE_GROUP_WOOL = 12;
	const CREATIVE_GROUP_WOOLCARPET = 13;
	const CREATIVE_GROUP_STAINEDCLAY = 14;
	const CREATIVE_GROUP_ORE = 15;
	const CREATIVE_GROUP_STONE = 16;
	const CREATIVE_GROUP_LOGS = 17;
	const CREATIVE_GROUP_LEAVES = 18;
	const CREATIVE_GROUP_SAPLING = 19;
	const CREATIVE_GROUP_SEED = 20;
	const CREATIVE_GROUP_CROP = 21;
	const CREATIVE_GROUP_GRASS = 22;
	const CREATIVE_GROUP_FLOWER = 23;
	const CREATIVE_GROUP_DYE = 24;
	const CREATIVE_GROUP_MUSHROOM = 25;
	const CREATIVE_GROUP_MOBEGG = 26;
	const CREATIVE_GROUP_HELMET = 27;
	const CREATIVE_GROUP_CHESTPLATE = 28;
	const CREATIVE_GROUP_LEGGINGS = 29;
	const CREATIVE_GROUP_BOOTS = 30;
	const CREATIVE_GROUP_SWORD = 31;
	const CREATIVE_GROUP_AXE = 32;
	const CREATIVE_GROUP_PICKAXE = 33;
	const CREATIVE_GROUP_SHOVEL = 34;
	const CREATIVE_GROUP_HOE = 35;
	const CREATIVE_GROUP_ARROW = 36;
	const CREATIVE_GROUP_COOKEDFOOD = 37;
	const CREATIVE_GROUP_MISCFOOD = 38;
	const CREATIVE_GROUP_BED = 39;
	const CREATIVE_GROUP_ANVIL = 40;
	const CREATIVE_GROUP_CHEST = 41;
	const CREATIVE_GROUP_SIGN = 42;
	const CREATIVE_GROUP_RAIL = 43;
	const CREATIVE_GROUP_MINECART = 44;

	private static $creativeGroupData = [
		self::CREATIVE_GROUP_PLANKS => ['name' => 'itemGroup.name.planks', 'item' => self::PLANK],
		self::CREATIVE_GROUP_WALLS => ['name' => 'itemGroup.name.walls', 'item' => self::COBBLESTONE_WALL],
		self::CREATIVE_GROUP_FENCE => ['name' => 'itemGroup.name.fence', 'item' => self::FENCE],
		self::CREATIVE_GROUP_FENCEGATE => ['name' => 'itemGroup.name.fenceGate', 'item' => self::FENCE_GATE],
		self::CREATIVE_GROUP_STAIRS => ['name' => 'itemGroup.name.stairs', 'item' => self::COBBLE_STAIRS],
		self::CREATIVE_GROUP_DOOR => ['name' => 'itemGroup.name.door', 'item' => self::WOODEN_DOOR],
		self::CREATIVE_GROUP_TRAPDOOR => ['name' => 'itemGroup.name.trapdoor', 'item' => self::TRAPDOOR],
		self::CREATIVE_GROUP_GLASS => ['name' => 'itemGroup.name.glass', 'item' => self::GLASS],
		self::CREATIVE_GROUP_GLASSPANE => ['name' => 'itemGroup.name.glassPane', 'item' => self::GLASS_PANE],
		self::CREATIVE_GROUP_SLAB => ['name' => 'itemGroup.name.slab', 'item' => self::SLAB],
		self::CREATIVE_GROUP_STONEBRICK => ['name' => 'itemGroup.name.stoneBrick', 'item' => self::STONE_BRICK],
		self::CREATIVE_GROUP_SANDSTONE => ['name' => 'itemGroup.name.sandstone', 'item' => self::SANDSTONE],
		self::CREATIVE_GROUP_WOOL => ['name' => 'itemGroup.name.wool', 'item' => self::WOOL],
		self::CREATIVE_GROUP_WOOLCARPET => ['name' => 'itemGroup.name.woolCarpet', 'item' => self::CARPET],
		self::CREATIVE_GROUP_STAINEDCLAY => ['name' => 'itemGroup.name.stainedClay', 'item' => self::CLAY_BLOCK],
		self::CREATIVE_GROUP_ORE => ['name' => 'itemGroup.name.ore', 'item' => self::IRON_ORE],
		self::CREATIVE_GROUP_STONE => ['name' => 'itemGroup.name.stone', 'item' => self::STONE],
		self::CREATIVE_GROUP_LOGS => ['name' => 'itemGroup.name.log', 'item' => self::LOG],
		self::CREATIVE_GROUP_LEAVES => ['name' => 'itemGroup.name.wood', 'item' => self::LEAVES],
		self::CREATIVE_GROUP_SAPLING => ['name' => 'itemGroup.name.sapling', 'item' => self::SAPLING],
		self::CREATIVE_GROUP_SEED => ['name' => 'itemGroup.name.seed', 'item' => self::SEEDS],
		self::CREATIVE_GROUP_CROP => ['name' => 'itemGroup.name.crop', 'item' => self::WHEAT],
		self::CREATIVE_GROUP_GRASS => ['name' => 'itemGroup.name.grass', 'item' => self::TALL_GRASS],
		self::CREATIVE_GROUP_FLOWER => ['name' => 'itemGroup.name.flower', 'item' => self::DANDELION],
		self::CREATIVE_GROUP_DYE => ['name' => 'itemGroup.name.dye', 'item' => self::DYE],
		self::CREATIVE_GROUP_MUSHROOM => ['name' => 'itemGroup.name.mushroom', 'item' => self::BROWN_MUSHROOM],
		self::CREATIVE_GROUP_MOBEGG => ['name' => 'itemGroup.name.mobEgg', 'item' => self::SPAWN_EGG],
		self::CREATIVE_GROUP_HELMET => ['name' => 'itemGroup.name.helmet', 'item' => self::LEATHER_CAP],
		self::CREATIVE_GROUP_CHESTPLATE => ['name' => 'itemGroup.name.chestplate', 'item' => self::LEATHER_TUNIC],
		self::CREATIVE_GROUP_LEGGINGS => ['name' => 'itemGroup.name.leggings', 'item' => self::LEATHER_PANTS],
		self::CREATIVE_GROUP_BOOTS => ['name' => 'itemGroup.name.boots', 'item' => self::LEATHER_BOOTS],
		self::CREATIVE_GROUP_SWORD => ['name' => 'itemGroup.name.sword', 'item' => self::WOODEN_SWORD],
		self::CREATIVE_GROUP_AXE => ['name' => 'itemGroup.name.axe', 'item' => self::WOODEN_AXE],
		self::CREATIVE_GROUP_PICKAXE => ['name' => 'itemGroup.name.pickaxe', 'item' => self::WOODEN_PICKAXE],
		self::CREATIVE_GROUP_SHOVEL => ['name' => 'itemGroup.name.shovel', 'item' => self::WOODEN_SHOVEL],
		self::CREATIVE_GROUP_HOE => ['name' => 'itemGroup.name.hoe', 'item' => self::WOODEN_HOE],
		self::CREATIVE_GROUP_ARROW => ['name' => 'itemGroup.name.arrow', 'item' => self::ARROW],
		self::CREATIVE_GROUP_COOKEDFOOD => ['name' => 'itemGroup.name.cookedFood', 'item' => self::COOKED_CHICKEN],
		self::CREATIVE_GROUP_MISCFOOD => ['name' => 'itemGroup.name.miscFood', 'item' => self::BREAD],
		self::CREATIVE_GROUP_BED => ['name' => 'itemGroup.name.bed', 'item' => self::BED],
		self::CREATIVE_GROUP_ANVIL => ['name' => 'itemGroup.name.anvil', 'item' => self::ANVIL],
		self::CREATIVE_GROUP_CHEST => ['name' => 'itemGroup.name.chest', 'item' => self::CHEST],
		self::CREATIVE_GROUP_SIGN => ['name' => 'itemGroup.name.sign', 'item' => self::SIGN],
		self::CREATIVE_GROUP_RAIL => ['name' => 'itemGroup.name.rail', 'item' => self::RAIL],
		self::CREATIVE_GROUP_MINECART => ['name' => 'itemGroup.name.minecart', 'item' => self::MINECART],
	];
	
	private static $creative = [];
	
	private static function initCreativeItems(){
		self::clearCreativeItems();
		
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 0), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 1), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 0), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 1), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 2), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 3), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 4), self::CREATIVE_GROUP_PLANKS);
		self::addCreativeItem(Item::get(Item::WOODEN_PLANKS, 5), self::CREATIVE_GROUP_PLANKS);
		
		self::addCreativeItem(Item::get(Item::COBBLESTONE_WALL, 0), self::CREATIVE_GROUP_WALLS);
		self::addCreativeItem(Item::get(Item::COBBLESTONE_WALL, 1), self::CREATIVE_GROUP_WALLS);        
		self::addCreativeItem(Item::get(Item::STONE_WALL, 2), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 3), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 4), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 5), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 6), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 7), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 8), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 9), self::CREATIVE_GROUP_WALLS);      
		self::addCreativeItem(Item::get(Item::STONE_WALL, 10), self::CREATIVE_GROUP_WALLS);     
		self::addCreativeItem(Item::get(Item::STONE_WALL, 11), self::CREATIVE_GROUP_WALLS);     
		self::addCreativeItem(Item::get(Item::STONE_WALL, 12), self::CREATIVE_GROUP_WALLS);     
		self::addCreativeItem(Item::get(Item::STONE_WALL, 13), self::CREATIVE_GROUP_WALLS);             
		
		self::addCreativeItem(Item::get(Item::FENCE, 0), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::FENCE, 1), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::FENCE, 2), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::FENCE, 3), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::FENCE, 4), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::FENCE, 5), self::CREATIVE_GROUP_FENCE);
		self::addCreativeItem(Item::get(Item::NETHER_BRICK_FENCE, 0), self::CREATIVE_GROUP_FENCE);
		
		self::addCreativeItem(Item::get(Item::FENCE_GATE, 0), self::CREATIVE_GROUP_FENCEGATE);
		self::addCreativeItem(Item::get(Item::FENCE_GATE_SPRUCE, 0), self::CREATIVE_GROUP_FENCEGATE);
		self::addCreativeItem(Item::get(Item::FENCE_GATE_BIRCH, 0), self::CREATIVE_GROUP_FENCEGATE);        
		self::addCreativeItem(Item::get(Item::FENCE_GATE_DARK_OAK, 0), self::CREATIVE_GROUP_FENCEGATE);
		self::addCreativeItem(Item::get(Item::FENCE_GATE_JUNGLE, 0), self::CREATIVE_GROUP_FENCEGATE);
		self::addCreativeItem(Item::get(Item::FENCE_GATE_ACACIA, 0), self::CREATIVE_GROUP_FENCEGATE);
		
		self::addCreativeItem(Item::get(Item::COBBLESTONE_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);         
		self::addCreativeItem(Item::get(Item::OAK_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::SPRUCE_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::BIRCH_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::JUNGLE_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::ACACIA_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::DARK_OAK_WOODEN_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::BRICK_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::STONE_BRICK_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::NETHER_BRICKS_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::SANDSTONE_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::QUARTZ_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		self::addCreativeItem(Item::get(Item::RED_SANDSTONE_STAIRS, 0), self::CREATIVE_GROUP_STAIRS);
		
		self::addCreativeItem(Item::get(Item::WOODEN_DOOR, 0), self::CREATIVE_GROUP_DOOR);
		
		self::addCreativeItem(Item::get(Item::TRAPDOOR, 0), self::CREATIVE_GROUP_TRAPDOOR);
		
		self::addCreativeItem(Item::get(Item::IRON_BARS, 0));
		
		self::addCreativeItem(Item::get(Item::GLASS, 0), self::CREATIVE_GROUP_GLASS);
		self::addColoredCreativeItem(self::STAINED_GLASS, self::CREATIVE_GROUP_GLASS);
		
		self::addCreativeItem(Item::get(Item::GLASS_PANE, 0), self::CREATIVE_GROUP_GLASSPANE);
		self::addColoredCreativeItem(self::STAINED_GLASS_PANE, self::CREATIVE_GROUP_GLASSPANE);
		
		self::addCreativeItem(Item::get(Item::LADDER, 0));      
		
		self::addCreativeItem(Item::get(Item::SLAB, 0), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 3), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 0), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 1), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 2), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 3), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 4), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::WOODEN_SLAB, 5), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 4), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 5), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 6), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 1), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::SLAB, 7), self::CREATIVE_GROUP_SLAB);
		
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 0), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 1), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 2), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 3), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 4), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 5), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 6), self::CREATIVE_GROUP_SLAB);
		self::addCreativeItem(Item::get(Item::STONE_SLAB2, 7), self::CREATIVE_GROUP_SLAB);
		
		self::addCreativeItem(Item::get(Item::BRICKS, 0));
		self::addCreativeItem(Item::get(Item::STONE_BRICKS, 0), self::CREATIVE_GROUP_STONEBRICK);
		self::addCreativeItem(Item::get(Item::STONE_BRICKS, 1), self::CREATIVE_GROUP_STONEBRICK);
		self::addCreativeItem(Item::get(Item::STONE_BRICKS, 2), self::CREATIVE_GROUP_STONEBRICK);
		self::addCreativeItem(Item::get(Item::STONE_BRICKS, 3), self::CREATIVE_GROUP_STONEBRICK);
		self::addCreativeItem(Item::get(Item::END_BRICKS, 0), self::CREATIVE_GROUP_STONEBRICK);
		self::addCreativeItem(Item::get(Item::PRISMARINE_CRYSTAL, 0));
		
		self::addCreativeItem(Item::get(Item::COBBLESTONE, 0));
		self::addCreativeItem(Item::get(Item::MOSS_STONE, 0));
		self::addCreativeItem(Item::get(Item::SANDSTONE, 0), self::CREATIVE_GROUP_SANDSTONE);
		self::addCreativeItem(Item::get(Item::SANDSTONE, 1), self::CREATIVE_GROUP_SANDSTONE);
		self::addCreativeItem(Item::get(Item::SANDSTONE, 2), self::CREATIVE_GROUP_SANDSTONE);
		self::addCreativeItem(Item::get(Item::COAL_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::GOLD_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::IRON_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::EMERALD_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::DIAMOND_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::LAPIS_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::QUARTZ_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::QUARTZ_BLOCK, 1));
		self::addCreativeItem(Item::get(Item::QUARTZ_BLOCK, 2));
		self::addCreativeItem(Item::get(Item::SLIME_BLOCK, 0));
		
		self::addCreativeItem(Item::get(Item::HAY_BALE, 0));
		self::addCreativeItem(Item::get(Item::BONE_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::NETHER_BRICKS, 0));
		self::addCreativeItem(Item::get(Item::RED_NETHER_BRICK, 0));
		
		self::addColoredCreativeItem(Item::WOOL, self::CREATIVE_GROUP_WOOL);
		self::addColoredCreativeItem(Item::CARPET, self::CREATIVE_GROUP_WOOLCARPET);
		
		self::addCreativeItem(Item::get(Item::CLAY_BLOCK, 0), self::CREATIVE_GROUP_STAINEDCLAY);
		self::addCreativeItem(Item::get(Item::HARDENED_CLAY, 0), self::CREATIVE_GROUP_STAINEDCLAY);
		self::addColoredCreativeItem(Item::STAINED_CLAY, self::CREATIVE_GROUP_STAINEDCLAY);
		
		self::addCreativeItem(Item::get(Item::PURPUR_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::DIRT, 0));
		self::addCreativeItem(Item::get(Item::GRASS, 0));
		self::addCreativeItem(Item::get(Item::PODZOL, 0));
		self::addCreativeItem(Item::get(Item::MYCELIUM, 0));
		
		self::addCreativeItem(Item::get(Item::STONE, 0), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 1), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 2), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 3), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 4), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 5), self::CREATIVE_GROUP_STONE);
		self::addCreativeItem(Item::get(Item::STONE, 6), self::CREATIVE_GROUP_STONE);
		
		self::addCreativeItem(Item::get(Item::IRON_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::GOLD_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::DIAMOND_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::LAPIS_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::REDSTONE_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::COAL_ORE, 0), self::CREATIVE_GROUP_ORE);
		self::addCreativeItem(Item::get(Item::EMERALD_ORE, 0), self::CREATIVE_GROUP_ORE);
		
		self::addCreativeItem(Item::get(Item::GRAVEL, 0));
		self::addCreativeItem(Item::get(Item::SAND, 0));
		self::addCreativeItem(Item::get(Item::SAND, 1));
		self::addCreativeItem(Item::get(Item::CACTUS, 0));
		
		self::addCreativeItem(Item::get(Item::TRUNK, 0), self::CREATIVE_GROUP_LOGS);
		self::addCreativeItem(Item::get(Item::TRUNK, 1), self::CREATIVE_GROUP_LOGS);
		self::addCreativeItem(Item::get(Item::TRUNK, 2), self::CREATIVE_GROUP_LOGS);
		self::addCreativeItem(Item::get(Item::TRUNK, 3), self::CREATIVE_GROUP_LOGS);
		self::addCreativeItem(Item::get(Item::TRUNK2, 0), self::CREATIVE_GROUP_LOGS);
		self::addCreativeItem(Item::get(Item::TRUNK2, 1), self::CREATIVE_GROUP_LOGS);
		
		self::addCreativeItem(Item::get(Item::LEAVES, 0), self::CREATIVE_GROUP_LEAVES);
		self::addCreativeItem(Item::get(Item::LEAVES, 1), self::CREATIVE_GROUP_LEAVES);
		self::addCreativeItem(Item::get(Item::LEAVES, 2), self::CREATIVE_GROUP_LEAVES);
		self::addCreativeItem(Item::get(Item::LEAVES, 3), self::CREATIVE_GROUP_LEAVES);
		self::addCreativeItem(Item::get(Item::LEAVES2, 0), self::CREATIVE_GROUP_LEAVES);
		self::addCreativeItem(Item::get(Item::LEAVES2, 1), self::CREATIVE_GROUP_LEAVES);
		
		self::addCreativeItem(Item::get(Item::SAPLING, 0), self::CREATIVE_GROUP_SAPLING);
		self::addCreativeItem(Item::get(Item::SAPLING, 1), self::CREATIVE_GROUP_SAPLING);
		self::addCreativeItem(Item::get(Item::SAPLING, 2), self::CREATIVE_GROUP_SAPLING);
		self::addCreativeItem(Item::get(Item::SAPLING, 3), self::CREATIVE_GROUP_SAPLING);
		self::addCreativeItem(Item::get(Item::SAPLING, 4), self::CREATIVE_GROUP_SAPLING);
		self::addCreativeItem(Item::get(Item::SAPLING, 5), self::CREATIVE_GROUP_SAPLING);
		
		self::addCreativeItem(Item::get(Item::SEEDS, 0), self::CREATIVE_GROUP_SEED);
		self::addCreativeItem(Item::get(Item::PUMPKIN_SEEDS, 0), self::CREATIVE_GROUP_SEED);
		self::addCreativeItem(Item::get(Item::MELON_SEEDS, 0), self::CREATIVE_GROUP_SEED);
		self::addCreativeItem(Item::get(Item::BEETROOT_SEEDS, 0), self::CREATIVE_GROUP_SEED);
		
		self::addCreativeItem(Item::get(Item::WHEAT, 0), self::CREATIVE_GROUP_CROP);        
		self::addCreativeItem(Item::get(Item::APPLE, 0), self::CREATIVE_GROUP_CROP);
		self::addCreativeItem(Item::get(Item::GOLDEN_APPLE, 0), self::CREATIVE_GROUP_CROP);
		
		self::addCreativeItem(Item::get(Item::MELON_BLOCK, 0), self::CREATIVE_GROUP_CROP);
		self::addCreativeItem(Item::get(Item::MELON, 0), self::CREATIVE_GROUP_CROP);
		self::addCreativeItem(Item::get(Item::PUMPKIN, 0), self::CREATIVE_GROUP_CROP);
		self::addCreativeItem(Item::get(Item::LIT_PUMPKIN, 0), self::CREATIVE_GROUP_CROP);
		
		self::addCreativeItem(Item::get(Item::TALL_GRASS, 1), self::CREATIVE_GROUP_GRASS);
		self::addCreativeItem(Item::get(Item::TALL_GRASS, 2), self::CREATIVE_GROUP_GRASS);
		self::addCreativeItem(Item::get(Item::DANDELION, 0), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_POPPY), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_BLUE_ORCHID), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_ALLIUM), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_AZURE_BLUET), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_RED_TULIP), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_ORANGE_TULIP), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_WHITE_TULIP), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_PINK_TULIP), self::CREATIVE_GROUP_FLOWER);
		self::addCreativeItem(Item::get(Item::RED_FLOWER, Flower::TYPE_OXEYE_DAISY), self::CREATIVE_GROUP_FLOWER);
		
		self::addColoredCreativeItem(Item::DYE, self::CREATIVE_GROUP_DYE);
		
		self::addCreativeItem(Item::get(Item::VINES, 0));
		self::addCreativeItem(Item::get(Item::WATER_LILY, 0));
		self::addCreativeItem(Item::get(Item::DEAD_BUSH, 0));
		self::addCreativeItem(Item::get(Item::SNOW_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::ICE, 0));
		self::addCreativeItem(Item::get(Item::SNOW_LAYER, 0));
		
		self::addCreativeItem(Item::get(Item::BROWN_MUSHROOM, 0), self::CREATIVE_GROUP_MUSHROOM);
		self::addCreativeItem(Item::get(Item::RED_MUSHROOM, 0), self::CREATIVE_GROUP_MUSHROOM);
		self::addCreativeItem(Item::get(Item::SUGAR_CANES, 0));
		self::addCreativeItem(Item::get(Item::SUGAR, 0));
		self::addCreativeItem(Item::get(Item::BONE, 0));
		self::addCreativeItem(Item::get(Item::COBWEB, 0));
		self::addCreativeItem(Item::get(Item::MONSTER_SPAWNER, 0));     
		
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 15), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 10), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 11), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 12), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 13), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 14), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 22), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 16), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 19), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 18), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 33), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 38), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 39), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 34), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 37), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 35), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 32), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 36), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 17), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 40), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 42), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 41), self::CREATIVE_GROUP_MOBEGG);
		self::addCreativeItem(Item::get(Item::SPAWN_EGG, 43), self::CREATIVE_GROUP_MOBEGG);
		
		self::addCreativeItem(Item::get(Item::OBSIDIAN, 0));
		self::addCreativeItem(Item::get(Item::BEDROCK, 0));
		self::addCreativeItem(Item::get(Item::NETHERRACK, 0));
		self::addCreativeItem(Item::get(Item::NETHER_WART_BLOCK_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::MAGMA, 0));
		self::addCreativeItem(Item::get(Item::END_STONE, 0));
		
		self::addCreativeItem(Item::get(Item::CHORUS_FLOWER, 0));
		self::addCreativeItem(Item::get(Item::CHORUS_PLANT, 0));
		self::addCreativeItem(Item::get(Item::SPONGE, 0));
		
		self::addCreativeItem(Item::get(Item::LEATHER_CAP, 0), self::CREATIVE_GROUP_HELMET);
		self::addCreativeItem(Item::get(Item::CHAIN_HELMET, 0), self::CREATIVE_GROUP_HELMET);
		self::addCreativeItem(Item::get(Item::IRON_HELMET, 0), self::CREATIVE_GROUP_HELMET);
		self::addCreativeItem(Item::get(Item::DIAMOND_HELMET, 0), self::CREATIVE_GROUP_HELMET);
		self::addCreativeItem(Item::get(Item::GOLD_HELMET, 0), self::CREATIVE_GROUP_HELMET);
		
		self::addCreativeItem(Item::get(Item::LEATHER_TUNIC, 0), self::CREATIVE_GROUP_CHESTPLATE);
		self::addCreativeItem(Item::get(Item::CHAIN_CHESTPLATE, 0), self::CREATIVE_GROUP_CHESTPLATE);
		self::addCreativeItem(Item::get(Item::IRON_CHESTPLATE, 0), self::CREATIVE_GROUP_CHESTPLATE);
		self::addCreativeItem(Item::get(Item::DIAMOND_CHESTPLATE, 0), self::CREATIVE_GROUP_CHESTPLATE);
		self::addCreativeItem(Item::get(Item::GOLD_CHESTPLATE, 0), self::CREATIVE_GROUP_CHESTPLATE);
		
		self::addCreativeItem(Item::get(Item::LEATHER_PANTS, 0), self::CREATIVE_GROUP_LEGGINGS);
		self::addCreativeItem(Item::get(Item::CHAIN_LEGGINGS, 0), self::CREATIVE_GROUP_LEGGINGS);
		self::addCreativeItem(Item::get(Item::IRON_LEGGINGS, 0), self::CREATIVE_GROUP_LEGGINGS);
		self::addCreativeItem(Item::get(Item::DIAMOND_LEGGINGS, 0), self::CREATIVE_GROUP_LEGGINGS);
		self::addCreativeItem(Item::get(Item::GOLD_LEGGINGS, 0), self::CREATIVE_GROUP_LEGGINGS);
		
		self::addCreativeItem(Item::get(Item::LEATHER_BOOTS, 0), self::CREATIVE_GROUP_BOOTS);
		self::addCreativeItem(Item::get(Item::CHAIN_BOOTS, 0), self::CREATIVE_GROUP_BOOTS);
		self::addCreativeItem(Item::get(Item::IRON_BOOTS, 0), self::CREATIVE_GROUP_BOOTS);
		self::addCreativeItem(Item::get(Item::DIAMOND_BOOTS, 0), self::CREATIVE_GROUP_BOOTS);
		self::addCreativeItem(Item::get(Item::GOLD_BOOTS, 0), self::CREATIVE_GROUP_BOOTS);
		
		self::addCreativeItem(Item::get(Item::WOODEN_SWORD, 0), self::CREATIVE_GROUP_SWORD);
		self::addCreativeItem(Item::get(Item::STONE_SWORD, 0), self::CREATIVE_GROUP_SWORD);
		self::addCreativeItem(Item::get(Item::IRON_SWORD, 0), self::CREATIVE_GROUP_SWORD);
		self::addCreativeItem(Item::get(Item::DIAMOND_SWORD, 0), self::CREATIVE_GROUP_SWORD);
		self::addCreativeItem(Item::get(Item::GOLD_SWORD, 0), self::CREATIVE_GROUP_SWORD);
		
		self::addCreativeItem(Item::get(Item::WOODEN_AXE, 0), self::CREATIVE_GROUP_AXE);
		self::addCreativeItem(Item::get(Item::STONE_AXE, 0), self::CREATIVE_GROUP_AXE);
		self::addCreativeItem(Item::get(Item::IRON_AXE, 0), self::CREATIVE_GROUP_AXE);
		self::addCreativeItem(Item::get(Item::DIAMOND_AXE, 0), self::CREATIVE_GROUP_AXE);
		self::addCreativeItem(Item::get(Item::GOLD_AXE, 0), self::CREATIVE_GROUP_AXE); 
		
		self::addCreativeItem(Item::get(Item::WOODEN_PICKAXE, 0), self::CREATIVE_GROUP_PICKAXE);
		self::addCreativeItem(Item::get(Item::STONE_PICKAXE, 0), self::CREATIVE_GROUP_PICKAXE);
		self::addCreativeItem(Item::get(Item::IRON_PICKAXE, 0), self::CREATIVE_GROUP_PICKAXE);
		self::addCreativeItem(Item::get(Item::DIAMOND_PICKAXE, 0), self::CREATIVE_GROUP_PICKAXE);
		self::addCreativeItem(Item::get(Item::GOLD_PICKAXE, 0), self::CREATIVE_GROUP_PICKAXE);
		
		self::addCreativeItem(Item::get(Item::WOODEN_SHOVEL, 0), self::CREATIVE_GROUP_SHOVEL);
		self::addCreativeItem(Item::get(Item::STONE_SHOVEL, 0), self::CREATIVE_GROUP_SHOVEL);
		self::addCreativeItem(Item::get(Item::IRON_SHOVEL, 0), self::CREATIVE_GROUP_SHOVEL);
		self::addCreativeItem(Item::get(Item::DIAMOND_SHOVEL, 0), self::CREATIVE_GROUP_SHOVEL);
		self::addCreativeItem(Item::get(Item::GOLD_SHOVEL, 0), self::CREATIVE_GROUP_SHOVEL);
		
		self::addCreativeItem(Item::get(Item::WOODEN_HOE, 0), self::CREATIVE_GROUP_HOE);
		self::addCreativeItem(Item::get(Item::STONE_HOE, 0), self::CREATIVE_GROUP_HOE);
		self::addCreativeItem(Item::get(Item::IRON_HOE, 0), self::CREATIVE_GROUP_HOE);
		self::addCreativeItem(Item::get(Item::DIAMOND_HOE, 0), self::CREATIVE_GROUP_HOE);
		self::addCreativeItem(Item::get(Item::GOLD_HOE, 0), self::CREATIVE_GROUP_HOE);
		
		self::addCreativeItem(Item::get(Item::BOW, 0));
		self::addCreativeItem(Item::get(Item::ARROW, 0), self::CREATIVE_GROUP_ARROW);
		
		self::addCreativeItem(Item::get(Item::COOKED_FISH, 0), self::CREATIVE_GROUP_COOKEDFOOD);
		self::addCreativeItem(Item::get(Item::COOKED_FISH, 1), self::CREATIVE_GROUP_COOKEDFOOD);
		self::addCreativeItem(Item::get(Item::CAKE, 0), self::CREATIVE_GROUP_MISCFOOD);
		self::addCreativeItem(Item::get(Item::BREAD, 0), self::CREATIVE_GROUP_MISCFOOD);
		
		self::addCreativeItem(Item::get(Item::FISHING_ROD, 0));
		self::addCreativeItem(Item::get(Item::SNOWBALL));
		self::addCreativeItem(Item::get(Item::SHEARS, 0));
		self::addCreativeItem(Item::get(Item::FLINT_AND_STEEL, 0));
		self::addCreativeItem(Item::get(Item::CLOCK, 0));
		self::addCreativeItem(Item::get(Item::COMPASS, 0));
		self::addCreativeItem(Item::get(Item::STICKS, 0));
		self::addCreativeItem(Item::get(Item::BED, 0), self::CREATIVE_GROUP_BED);
		self::addCreativeItem(Item::get(Item::TORCH, 0));
		
		self::addCreativeItem(Item::get(Item::WORKBENCH, 0));
		self::addCreativeItem(Item::get(Item::FURNACE, 0));
		self::addCreativeItem(Item::get(Item::ANVIL, 0), self::CREATIVE_GROUP_ANVIL);
		self::addCreativeItem(Item::get(Item::ANVIL, 4), self::CREATIVE_GROUP_ANVIL);
		self::addCreativeItem(Item::get(Item::ANVIL, 8), self::CREATIVE_GROUP_ANVIL);
		self::addCreativeItem(Item::get(Item::ENCHANT_TABLE, 0));
		self::addCreativeItem(Item::get(Item::BOOKSHELF, 0));
		self::addCreativeItem(Item::get(Item::CHEST, 0), self::CREATIVE_GROUP_CHEST);
		self::addCreativeItem(Item::get(Item::ENDER_CHEST, 0), self::CREATIVE_GROUP_CHEST);
		
		self::addCreativeItem(Item::get(Item::GLOWSTONE_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::REDSTONE_LAMP, 0)); 
		self::addCreativeItem(Item::get(Item::SIGN, 0), self::CREATIVE_GROUP_SIGN);
		self::addCreativeItem(Item::get(Item::PAINTING, 0));
		
		self::addCreativeItem(Item::get(Item::BOWL, 0));
		self::addCreativeItem(Item::get(Item::BUCKET, 0));
		self::addCreativeItem(Item::get(Item::BUCKET, 1));
		self::addCreativeItem(Item::get(Item::BUCKET, 8));
		self::addCreativeItem(Item::get(Item::BUCKET, 10));
		// self::addCreativeItem(Item::get(Item::STONECUTTER, 0)); // crash 1.11.0.1
		self::addCreativeItem(Item::get(Item::END_PORTAL, 0));
		
		self::addCreativeItem(Item::get(Item::COAL, 0));
		self::addCreativeItem(Item::get(Item::COAL, 1));
		self::addCreativeItem(Item::get(Item::DIAMOND, 0));
		self::addCreativeItem(Item::get(Item::IRON_INGOT, 0));
		self::addCreativeItem(Item::get(Item::GOLD_INGOT, 0));
		self::addCreativeItem(Item::get(Item::EMERALD, 0));
		
		self::addCreativeItem(Item::get(Item::QUARTZ, 0));
		self::addCreativeItem(Item::get(Item::CLAY, 0));
		
		self::addCreativeItem(Item::get(Item::STRING, 0));
		self::addCreativeItem(Item::get(Item::FEATHER, 0));
		self::addCreativeItem(Item::get(Item::FLINT, 0));
		self::addCreativeItem(Item::get(Item::LEATHER, 0));     
		self::addCreativeItem(Item::get(Item::END_ROD, 0));
		self::addCreativeItem(Item::get(Item::PAPER, 0));
		
		self::addCreativeItem(Item::get(Item::RAIL, 0), self::CREATIVE_GROUP_RAIL);
		self::addCreativeItem(Item::get(Item::MINECART, 0), self::CREATIVE_GROUP_MINECART);
		
		self::addCreativeItem(Item::get(Item::REDSTONE, 0));
		self::addCreativeItem(Item::get(Item::REDSTONE_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::HOPPER, 0));
		self::addCreativeItem(Item::get(Item::TNT, 0));
		self::addCreativeItem(Item::get(Item::NOTE_BLOCK, 0));
		self::addCreativeItem(Item::get(Item::DROPPER, 0));
		
	}
	
	private static function addColoredCreativeItem($itemId, $creativeGroup) {
		self::addCreativeItem(Item::get($itemId, Block::COLOR_WHITE), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_ORANGE), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_MAGENTA), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_LIGHT_BLUE), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_YELLOW), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_LIME), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_PINK), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_GRAY), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_LIGHT_GRAY), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_CYAN), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_PURPLE), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_BLUE), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_BROWN), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_GREEN), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_RED), $creativeGroup);
		self::addCreativeItem(Item::get($itemId, Block::COLOR_BLACK), $creativeGroup);
	}
	
    private static function initFood(){
        self::$food[] = Item::COOKIE;
        self::$food[] = Item::MELON;
        self::$food[] = Item::RAW_BEEF;
        self::$food[] = Item::COOKED_BEEF;
        self::$food[] = Item::RAW_CHICKEN;
        self::$food[] = Item::COOKED_CHICKEN;
        self::$food[] = Item::CARROT;
        self::$food[] = Item::POTATO;
        self::$food[] = Item::BAKED_POTATO;
        self::$food[] = Item::PUMPKIN_PIE;
        self::$food[] = Item::BREAD;
        self::$food[] = Item::APPLE;
        self::$food[] = Item::GOLDEN_APPLE;
        self::$food[] = Item::ENCHANTED_GOLDEN_APPLE;
        self::$food[] = Item::RAW_FISH;
        self::$food[] = Item::COOKED_FISH;
        self::$food[] = Item::RAW_PORKCHOP;
        self::$food[] = Item::COOKED_PORKCHOP;
        self::$food[] = Item::RAW_MUTTON;
        self::$food[] = Item::COOKED_MUTTON;
        self::$food[] = Item::RAW_RABBIT;
        self::$food[] = Item::COOKED_RABBIT;
        self::$food[] = Item::RAW_SALMON;
        self::$food[] = Item::COOKED_SALMON;
        self::$food[] = Item::RABBIT_STEW;
        self::$food[] = Item::CHORUS_FRUIT;
    }

    public static function clearCreativeItems(){
        Item::$creative = [];
    }

    public static function getCreativeItems(){
        return Item::$creative;
    }
	
	public static function getCreativeGroups(){
        return Item::$creativeGroupData;
    }
	
    public static function addCreativeItem(Item $item, $creativeGroup = self::CREATIVE_GROUP_NONE){
        Item::$creative[] = ['item' => Item::get($item->getId(), $item->getDamage()), 'group' => $creativeGroup];
    }
	
    public static function removeCreativeItem(Item $item){
        $index = self::getCreativeItemIndex($item);
        if($index !== -1){
            unset(Item::$creative[$index]);
        }
    }

    public static function isCreativeItem(Item $item){
        foreach(Item::$creative as $i => $d){
            if($item->equals($d['item'], !$item->isTool())){
                return true;
            }
        }

        return false;
    }

    /**
     * @param $index
     * @return Item
     */
    public static function getCreativeItem($index){
        return isset(Item::$creative[$index]) ? Item::$creative[$index]['item'] : null;
    }

    /**
     * @param Item $item
     * @return int
     */
    public static function getCreativeItemIndex(Item $item){
        foreach(Item::$creative as $i => $d){
            if($item->equals($d['item'], !$item->isTool())){
                return $i;
            }
        }

        return -1;
    }

    public static function get($id, $meta = 0, $count = 1, $tags = ""){
        try{
            if (!isset(self::$list[$id])) {
                if ($id < 256 && isset(Block::$list[$id]) && !is_null(Block::$list[$id])) {
                    $class = Block::$list[$id];
                    return (new self::$itemBlockClass(new $class($meta), $meta, $count))->setCompound($tags);
                }
                return (new Item($id, $meta, $count))->setCompound($tags);
            }
            $class = self::$list[$id];
            return (new $class($meta, $count))->setCompound($tags);
        }catch(\RuntimeException $e){
            return (new Item($id, $meta, $count))->setCompound($tags);
        }
    }

    public static function fromString($str, $multiple = false){
        if($multiple === true){
            $blocks = [];
            foreach(explode(",", $str) as $b){
                $blocks[] = self::fromString($b, false);
            }

            return $blocks;
        }else{
            $b = explode(":", str_replace([" ", "minecraft:"], ["_", ""], trim($str)));
            if(!isset($b[1])){
                $meta = 0;
            }else{
                $meta = $b[1] & 0x7FFF;
            }

            if(defined(Item::class . "::" . strtoupper($b[0]))){
                $item = self::get(constant(Item::class . "::" . strtoupper($b[0])), $meta);
                if($item->getId() === self::AIR and strtoupper($b[0]) !== "AIR"){
                    $item = self::get($b[0] & 0xFFFF, $meta);
                }
            }else{
                $item = self::get($b[0] & 0xFFFF, $meta);
            }

            return $item;
        }
    }

    public function __construct($id, $meta = 0, $count = 1, $name = "Unknown", $obtainTime = null){
        $this->id = $id & 0xffff;
        $this->meta = $meta !== null ? $meta & 0x7fff : null;
        $this->count = (int) $count;
        $this->name = $name;
        if($obtainTime == null){
            $obtainTime = time();
        }
        if(!isset($this->block) and $this->id <= 0xff and isset(Block::$list[$this->id])){
            $this->block = Block::get($this->id, $this->meta);
            $this->name = $this->block->getName();
        }
        if($this->name == "Unknown" && isset(Item::$names[$this->id])){
            $this->name = Item::$names[$this->id];
        }
    }

	/**
	 * Sets the Item's NBT
	 *
	 * @param CompoundTag|string|null $tags
	 *
	 * @return $this
	 */
    public function setCompound($tags){
        if($tags instanceof Compound){
            $this->setNamedTag($tags);
        }else{
            $this->tags = $tags;
            $this->cachedNBT = null;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCompound(){
        return $this->tags;
    }

    public function hasCompound(){
        return $this->tags !== "" and $this->tags !== null;
    }

    public function hasCustomBlockData(){
        if(!$this->hasCompound()){
            return false;
        }

        $tag = $this->getNamedTag();
        if(isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof Compound){
            return true;
        }

        return false;
    }

    /**
	 * @return $this
	 */
    public function clearCustomBlockData(){
        if(!$this->hasCompound()){
            return $this;
        }
        $tag = $this->getNamedTag();

        if(isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof Compound){
            unset($tag->display->BlockEntityTag);
            $this->setNamedTag($tag);
        }

        return $this;
    }

	/**
	 * @param CompoundTag $compound
	 *
	 * @return $this
	 */
    public function setCustomBlockData(Compound $compound){
        $tags = clone $compound;
        $tags->setName("BlockEntityTag");

        if(!$this->hasCompound()){
            $tag = new Compound("", []);
        }else{
            $tag = $this->getNamedTag();
        }

        $tag->BlockEntityTag = $tags;
        $this->setNamedTag($tag);

        return $this;
    }

    public function getCustomBlockData(){
        if(!$this->hasCompound()){
            return null;
        }

        $tag = $this->getNamedTag();
        if(isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof Compound){
            return $tag->BlockEntityTag;
        }

        return null;
    }

    public function hasEnchantments(){
        if(!$this->hasCompound()){
            return false;
        }

        $tag = $this->getNamedTag();
        if(isset($tag->ench)){
            $tag = $tag->ench;
            if($tag instanceof Enum){
                return true;
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return Enchantment|null
     */
    public function getEnchantment($id){
        if(!$this->hasEnchantments()){
            return null;
        }

        foreach($this->getNamedTag()->ench as $entry){
            if($entry["id"] === $id){
                $e = Enchantment::getEnchantment($entry["id"]);
                $e->setLevel($entry["lvl"]);
                return $e;
            }
        }

        return null;
    }

    /**
     * @param Enchantment $ench
     */
    public function addEnchantment(Enchantment $ench){
        if(!$this->hasCompound()){
            $tag = new Compound("", []);
        }else{
            $tag = $this->getNamedTag();
        }

        if(!isset($tag->ench)){
            $tag->ench = new Enum("ench", []);
            $tag->ench->setTagType(NBT::TAG_Compound);
        }

        $found = false;
        $maxIntIndex = -1;
        foreach($tag->ench as $k => $entry){
            if (is_numeric($k) && $k > $maxIntIndex) {
                $maxIntIndex = $k;
            }
            if($entry["id"] === $ench->getId()){
                $tag->ench->{$k} = new Compound("", [
                    "id" => new ShortTag("id", $ench->getId()),
                    "lvl" => new ShortTag("lvl", $ench->getLevel())
                ]);
                $found = true;
                break;
            }
        }

        if(!$found){
//			$tag->ench->{count($tag->ench) + 1} = new Compound("", [
            $tag->ench->{$maxIntIndex + 1} = new Compound("", [
                "id" => new ShortTag("id", $ench->getId()),
                "lvl" => new ShortTag("lvl", $ench->getLevel())
            ]);
        }

        $this->setNamedTag($tag);
    }

    /**
     * @return Enchantment[]
     */
    public function getEnchantments(){
        if(!$this->hasEnchantments()){
            return [];
        }

        $enchantments = [];

        foreach($this->getNamedTag()->ench as $entry){
            $e = Enchantment::getEnchantment($entry["id"]);
            $e->setLevel($entry["lvl"]);
            $enchantments[$e->getId()] = $e;
        }

        return $enchantments;
    }

    public function hasCustomName(){
        if(!$this->hasCompound()){
            return false;
        }

        $tag = $this->getNamedTag();
        if(isset($tag->display)){
            $tag = $tag->display;
            if($tag instanceof Compound and isset($tag->Name) and $tag->Name instanceof StringTag){
                return true;
            }
        }

        return false;
    }

    public function getCustomName(){
        if(!$this->hasCompound()){
            return "";
        }

        $tag = $this->getNamedTag();
        if(isset($tag->display)){
            $tag = $tag->display;
            if($tag instanceof Compound and isset($tag->Name) and $tag->Name instanceof StringTag){
                return $tag->Name->getValue();
            }
        }

        return "";
    }

    public function setCustomName($name){
        if((string) $name === ""){
            $this->clearCustomName();
        }

        if(!$this->hasCompound()){
            $tag = new Compound("", []);
        }else{
            $tag = $this->getNamedTag();
        }

        if(isset($tag->display) and $tag->display instanceof Compound){
            $tag->display->Name = new StringTag("Name", $name);
        }else{
            $tag->display = new Compound("display", [
                "Name" => new StringTag("Name", $name)
            ]);
        }

        $this->setCompound($tag);

        return $this;
    }

    public function setCustomColor($colorCode){
        if(!$this->hasCompound()){
            if (!is_int($colorCode)) {
                return $this;
            }
            $tag = new Compound("", []);
        }else{
            $tag = $this->getNamedTag();
        }
        if (!is_int($colorCode)) {
            unset($tag->customColor);
        } else {
            $tag->customColor = new IntTag("customColor", $colorCode);
        }

        $this->setCompound($tag);

        return $this;
    }

    public function clearCustomName(){
        if(!$this->hasCompound()){
            return $this;
        }
        $tag = $this->getNamedTag();

        if(isset($tag->display) and $tag->display instanceof Compound){
            unset($tag->display->Name);
            if($tag->display->getCount() === 0){
                unset($tag->display);
            }

            $this->setNamedTag($tag);
        }

        return $this;
    }

    public function getNamedTagEntry($name){
        $tag = $this->getNamedTag();
        if($tag !== null){
            return isset($tag->{$name}) ? $tag->{$name} : null;
        }

        return null;
    }

    public function getNamedTag(){
        if(!$this->hasCompound()){
            return null;
        }elseif($this->cachedNBT !== null){
            return $this->cachedNBT;
        }
        return $this->cachedNBT = self::parseCompound($this->tags);
    }

    public function setNamedTag(Compound $tag){
        if($tag->getCount() === 0){
            return $this->clearNamedTag();
        }

        $this->cachedNBT = $tag;
        $this->tags = self::writeCompound($tag);

        return $this;
    }

    public function clearNamedTag(){
        return $this->setCompound("");
    }

    public function getCount(){
        return $this->count;
    }

    public function setCount($count){
        $this->count = (int) $count;
    }

    final public function getName(){
        return $this->hasCustomName() ? $this->getCustomName() : $this->name;
    }

    final public function canBePlaced(){
        return $this->block !== null and $this->block->canBePlaced();
    }

    final public function isPlaceable(){
        return (($this->block instanceof Block) and $this->block->isPlaceable === true);
    }

    public function getBlock(){
        if($this->block instanceof Block){
            return clone $this->block;
        }else{
            return Block::get(self::AIR);
        }
    }

    final public function getId(){
        return $this->id;
    }

    public function getDamage(){
        return $this->meta;
    }

    public function setDamage($meta){
        $this->meta = $meta !== null ? $meta & 0x7FFF : null;
    }

    public function getMaxStackSize(){
        return 64;
    }

    final public function getFuelTime(){
        if(!isset(Fuel::$duration[$this->id])){
            return null;
        }
        if($this->id !== self::BUCKET or $this->meta === 10){
            return Fuel::$duration[$this->id];
        }

        return null;
    }

    /**
     * @param Entity|Block $object
     *
     * @return bool
     */
    public function useOn($object){
        return false;
    }

    /**
     * @return bool
     */
    public function isTool(){
        return false;
    }

    /**
     * @return int|bool
     */
    public function getMaxDurability(){
        return false;
    }

    public function isPickaxe(){
        return false;
    }

    public function isAxe(){
        return false;
    }

    public function isSword(){
        return false;
    }

    public function isShovel(){
        return false;
    }

    public function isHoe(){
        return false;
    }

    public function isShears(){
        return false;
    }

    final public function __toString(){
        return "Item " . $this->name . " (" . $this->id . ":" . ($this->meta === null ? "?" : $this->meta) . ")x" . $this->count . ($this->hasCompound() ? " tags:0x".bin2hex($this->getCompound()) : "");
    }

	public function nbtSerialize($slot = -1, $tagName = "") {
		$result = new Compound($tagName, [
			new ShortTag("id", $this->id),
			new ByteTag("Count", Binary::signByte($this->count)),
			new ShortTag("Damage", $this->meta)
		]);

		if ($this->hasCompound()) {
			$itemNBT = clone $this->getNamedTag();
			$itemNBT->setName("tag");
			$result->setTagType($itemNBT);
		}

		if ($slot !== -1) {
			$result->setByte("Slot", $slot);
		}
		return $result;
	}

	public static function nbtDeserialize(Compound $tag) {
		if (!$tag->hasTag("id") || !$tag->hasTag("Count")) {
			return self::get(0);
		}

		$count = Binary::unsignByte($tag->getByte("Count"));
		$meta = $tag->getShort("Damage", 0);

		$idTag = $tag->getTag("id");
		if ($idTag instanceof ShortTag) {
			$item = self::get($idTag->getValue(), $meta, $count);
		} elseif ($idTag instanceof StringTag) {
			try {
				$item = self::fromString($idTag->getValue());
			} catch (\InvalidArgumentException $e) {
				return self::get(Item::AIR, 0, 0);
			}
			$item->setDamage($meta);
			$item->setCount($count);
		} else {
			throw new \InvalidArgumentException("Item Compound ID must be an instance of StringTag or ShortTag, " . get_class($idTag) . " given");
		}

		$itemNBT = $tag->getCompound("tag");
		if ($itemNBT instanceof Compound) {
			$t = clone $itemNBT;
			$t->setName("");
			$item->setNamedTag($t);
		}
		return $item;
	}

    public function getDestroySpeed(Block $block, Player $player){
        return 1;
    }

    public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
        return false;
    }

    final public function equals(Item $item, $checkDamage = true, $checkCompound = true) {
        return $this->id === $item->getId() && ($checkDamage === false || $this->getDamage() === $item->getDamage()) && ($checkCompound === false || $this->getCompound() === $item->getCompound());
    }

    public final function deepEquals(Item $item, $checkDamage = true, $checkCompound = true){
        if($this->equals($item, $checkDamage, $checkCompound)){
            return true;
        }elseif($item->hasCompound() and $this->hasCompound()){
            return NBT::matchTree($this->getNamedTag(), $item->getNamedTag());
        }

        return false;
    }

    public function isFood(){
        return in_array($this->id, self::$food);
    }

    public function setObtainTime($time){
        $this->obtainTime = $time;
    }

    public function getObtainTime(){
        return $this->obtainTime;
    }

    public function isArmor(){
        return false;
    }

    public function hasLore(){
        if(!$this->hasCompound()){
            return false;
        }

        $tag = $this->getNamedTag();
        if(isset($tag->display)){
            $tag = $tag->display;
            if($tag instanceof Compound and isset($tag->Lore) and $tag->Lore instanceof Enum){
                return true;
            }
        }

        return false;
    }

    public function getLore(){
        if(!$this->hasCompound()){
            return "";
        }

        $tag = $this->getNamedTag();
        if(isset($tag->display)){
            $tag = $tag->display;
            if($tag instanceof Compound and isset($tag->Lore) and $tag->Lore instanceof Enum){
                return $tag->Lore->getValue();
            }
        }

        return [];
    }

    public function setLore($lore){
        if(!$this->hasCompound()){
            $tag = new Compound("", []);
        }else{
            $tag = $this->getNamedTag();
        }

        $loreArray = [];
        foreach ($lore as $loreText) {
            $loreArray[] = new StringTag("", $loreText);
        }

        if(isset($tag->display) and $tag->display instanceof Compound){
            $tag->display->Lore = new Enum("Lore", $loreArray);
        }else{
            $tag->display = new Compound("display", [
                "Lore" => new Enum("Lore", $loreArray)
            ]);
        }

        $this->setCompound($tag);

        return $this;
    }

    public static function registerItemBlock($className) {
        if (is_a($className, ItemBlock::class, true)) {
            self::$itemBlockClass = $className;
        }
    }

    public function getCanPlaceOnBlocks() {
        return $this->canPlaceOnBlocks;
    }

    public function getCanDestroyBlocks() {
        return $this->canDestroyBlocks;
    }

    public function addCanPlaceOnBlocks($blockName) {
        $this->canPlaceOnBlocks[$blockName] = $blockName;
    }

    public function addCanDestroyBlocks($blockName) {
        $this->canDestroyBlocks[$blockName] = $blockName;
    }

}
