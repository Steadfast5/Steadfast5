<?php

namespace pocketmine\network\protocol;

use pocketmine\network\protocol\v120\PlaySoundPacket;
use pocketmine\network\protocol\v120\StopSoundPacket;

interface NetworkSession {

    public function handleDataPacket(PEPacket $packet);
    public function handlePlaySound(PlaySoundPacket $packet) : bool;
    public function handleStopSound(StopSoundPacket $packet) : bool;

}
