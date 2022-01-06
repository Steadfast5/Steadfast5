<?php

namespace pocketmine\network\multiversion;

use pocketmine\network\protocol\Info;

class ItemsList {

	public static function initAll() {
		$result = [];
		$folderPath = __DIR__ . "/list/";
		$listFiles = array_diff(scandir($folderPath), ['..', '.']);
		foreach ($listFiles as $fileName) {
			$parts = explode(".", $fileName);
			$protocolNumber = (int) substr($parts[0], 11);
			$list = new ItemsList($folderPath . $fileName, $protocolNumber);
			$result[$protocolNumber] = $list;
		}
		krsort($list);
		return $list;
	}

	private $list = [];

	public function __construct($path, $protocolNumber) {
		$itemsData = json_decode(file_get_contents($path), true);
		if ($protocolNumber >= Info::PROTOCOL_419) {
			$this->list = $itemsData;
		}
	}

}
