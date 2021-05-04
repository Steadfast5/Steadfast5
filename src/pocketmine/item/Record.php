<?php

namespace pocketmine\item;

class Record extends Item {

	protected $soundId;
	protected $soundName;

	public function __construct($id, $soundName, $soundId) {
		parent::__construct($id, 0, "Record: " . $soundName);
		$this->soundName = $soundName;
		$this->soundId = $soundId;
	}

	public function getMaxStackSize() {
		return 1;
	}

	public function getUniqueId() {
		return $this->id;
	}

	public function getSoundName() {
		return $this->soundName;
	}

	public function getSoundId() {
		return $this->soundId;
	}

}
