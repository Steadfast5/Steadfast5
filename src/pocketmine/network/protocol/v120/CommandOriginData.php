<?php

namespace pocketmine\network\protocol\v120;

use pocketmine\utils\UUID;

class CommandOriginData {

	const ORIGIN_PLAYER = 0;
	const ORIGIN_BLOCK = 1;
	const ORIGIN_MINECART_BLOCK = 2;
	const ORIGIN_DEV_CONSOLE = 3;
	const ORIGIN_TEST = 4;
	const ORIGIN_AUTOMATION_PLAYER = 5;
	const ORIGIN_CLIENT_AUTOMATION = 6;
	const ORIGIN_DEDICATED_SERVER = 7;
	const ORIGIN_ENTITY = 8;
	const ORIGIN_VIRTUAL = 9;
	const ORIGIN_GAME_ARGUMENT = 10;
	const ORIGIN_ENTITY_SERVER = 11;

	public $type;
	public $uuid;
	public $requestId;
	public $playerEntityUniqueId;

}
