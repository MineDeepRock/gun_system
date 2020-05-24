<?php

namespace gun_system\models;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;

class GunSound
{
    private $text;

    public function __construct($text) {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->text;
    }

    public function play(Player $owner, int $volume = 10, int $pitch = 2,$pos = null): void {
        $pos = $pos ?? $owner->getPosition();

        $packet = new PlaySoundPacket();
        $packet->x = $pos->x;
        $packet->y = $pos->y;
        $packet->z = $pos->z;
        $packet->volume = $volume;
        $packet->pitch = $pitch;
        $packet->soundName = $this->text;
        $owner->sendDataPacket($packet);
    }

    public function playAround(Player $owner): void {
        $players = $owner->getServer()->getOnlinePlayers();
        self::play($owner, $this->text);

        foreach ($players as $player) {
            $packet = new PlaySoundPacket();
            $packet->x = $owner->x;
            $packet->y = $owner->y;
            $packet->z = $owner->z;
            $packet->volume = 3;
            $packet->pitch = 2;
            $packet->soundName = $this->text;
            $player->sendDataPacket($packet);
        }
    }
}