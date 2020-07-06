<?php


namespace gun_system\pmmp\service;


use gun_system\model\Gun;
use gun_system\model\GunType;
use gun_system\pmmp\sounds\ShootingSounds;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class ShootingService
{
    static function execute(TaskScheduler $scheduler, Player $player, Gun $gun): void {
        if ($player->getGamemode() === Player::SPECTATOR) return;

        if ($gun->getType()->equals(GunType::Shotgun())) {
            $i = 0;
            while ($i < 12) {
                SpawnBulletEntityService::execute($scheduler, $player, $gun->getPrecision(), $gun->getBulletSpeed());
                $i++;
            }
            GiveReactService::execute($player, $gun->getReaction());
        } else {
            SpawnBulletEntityService::execute($scheduler, $player, $gun->getPrecision(), $gun->getBulletSpeed());
        }
        PlaySoundsService::playAround($player->getLevel(), $player->getPosition(), ShootingSounds::ShootingSound($gun->getType()), 60);
        GiveReactService::execute($player, $gun->getReaction());
    }
}