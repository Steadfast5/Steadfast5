<?php

namespace pocketmine\level\generator\utils;

class CommonUtils {

	static function in_arrayi($needle, array $haystack) {
		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

}
