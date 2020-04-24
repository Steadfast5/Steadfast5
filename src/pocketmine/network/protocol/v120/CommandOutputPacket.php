<?php

namespace pocketmine\network\protocol\v120;

use pocketmine\network\protocol\Info120;
use pocketmine\network\protocol\PEPacket;

class CommandOutputPacket extends PEPacket {

	const NETWORK_ID = Info120::COMMAND_OUTPUT_PACKET;
	const PACKET_NAME = "COMMAND_OUTPUT_PACKET";

	public $originData;
	public $outputType;
	public $successCount;
	public $messages = [];
	public $unknownString;

	protected function encode($playerProtocol) {
		$this->putCommandOriginData($this->originData);
		$this->putByte($this->outputType);
		$this->putUnsignedVarInt($this->successCount);
		$this->putUnsignedVarInt(count($this->messages));
		foreach ($this->messages as $message) {
			$this->putCommandMessage($message);
		}
		if ($this->outputType === 4) {
			$this->putString($this->unknownString);
		}
	}

	protected function decode($playerProtocol) {
		$this->originData = $this->getCommandOriginData();
		$this->outputType = $this->getByte();
		$this->successCount = $this->getUnsignedVarInt();
		for ($i = 0, $size = $this->getUnsignedVarInt(); $i < $size; ++$i) {
			$this->messages[] = $this->getCommandMessage();
		}
		if ($this->outputType === 4) {
			$this->unknownString = $this->getString();
		}
	}

}