<?php


namespace gun_system\pmmp\service;


use gun_system\model\GunSound;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;

class PlaySoundsService
{
    static function play(Player $target, GunSound $sound, $pos = null): void {
        $pos = $pos ?? $target->getPosition();

        $packet = new PlaySoundPacket();
        $packet->x = $pos->x;
        $packet->y = $pos->y;
        $packet->z = $pos->z;
        $packet->volume = $sound->getVolume();
        $packet->pitch = $sound->getPitch();
        $packet->soundName = $sound->getName();
        $target->sendDataPacket($packet);
    }

    static function playAround(Level $level, Vector3 $pos, GunSound $sound, float $distance = null): void {
        $players = $level->getPlayers();

        foreach ($players as $player) {
            if ($distance === null) {
                self::play($player, $sound, $pos);
            } else if ($player->getPosition()->distance($pos) <= $distance) {
                self::play($player, $sound, $pos);
            }
        }
    }
}