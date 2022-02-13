<?php

namespace pocketmine\network\multiversion;

use pocketmine\network\protocol\Info;

class ItemList {

	public static function initAll() {
		$result = [];
		$folderPath = __DIR__ . "/list/";
		$listFiles = array_diff(scandir($folderPath), ['..', '.']);
		foreach ($listFiles as $fileName) {
			$parts = explode(".", $fileName);
			$protocolNumber = (int) substr($parts[0], 11);
			$list = json_decode(file_get_contents($folderPath . $fileName), true);
			$result[$protocolNumber] = $list;
		}
		krsort($result);
		return $result;
	}

}
