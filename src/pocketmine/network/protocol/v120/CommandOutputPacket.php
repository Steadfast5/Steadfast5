<?php

namespace pocketmine\network\protocol\v120;

use pocketmine\network\protocol\v120\CommandOriginData;
use pocketmine\network\protocol\v120\CommandOutputMessage;
use pocketmine\network\protocol\Info120;
use pocketmine\network\protocol\PEPacket;
use function count;

class CommandOutputPacket extends PEPacket {

	const NETWORK_ID = Info120::COMMAND_OUTPUT_PACKET;
	const PACKET_NAME = "COMMAND_OUTPUT_PACKET";

	public $originData;
	public $outputType;
	public $successCount;
	public $unknownString;
	public $messages = [];

	public function encode($playerProtocol) {
		$this->reset($playerProtocol);
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

	public function decode($playerProtocol) {
		$this->getHeader($playerProtocol);
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

	protected function getCommandMessage() {
		$message = new CommandOutputMessage();
		$message->isInternal = $this->getBool();
		$message->messageId = $this->getString();
		for ($i = 0, $size = $this->getUnsignedVarInt(); $i < $size; ++$i) {
			$message->parameters[] = $this->getString();
		}
		return $message;
	}

	protected function putCommandMessage(CommandOutputMessage $message) {
		$this->putBool($message->isInternal);
		$this->putString($message->messageId);
		$this->putUnsignedVarInt(count($message->parameters));
		foreach ($message->parameters as $parameter) {
			$this->putString($parameter);
		}
	}

}
