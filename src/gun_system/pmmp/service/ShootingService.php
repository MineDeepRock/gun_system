<?php


namespace gun_system\pmmp\service;


use gun_system\model\GunType;
use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\Precision;
use gun_system\model\performance\Reaction;
use gun_system\pmmp\sounds\ShootingSounds;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class ShootingService
{
    static function execute(TaskScheduler $scheduler, Player $player, GunType $gunType, Precision $precision, BulletSpeed $speed, Reaction $reaction): void {
        if ($player->getGamemode() === Player::SPECTATOR) return;

        if ($gunType->equals(GunType::Shotgun())) {
            $i = 0;
            while ($i < 12) {
                SpawnBulletEntityService::execute($scheduler, $player, $precision, $speed);
                $i++;
            }
            GiveReactService::execute($player, $reaction);
        } else {
            SpawnBulletEntityService::execute($scheduler, $player, $precision, $speed);
        }
        PlaySoundsService::playAround($player->getLevel(), $player->getPosition(), ShootingSounds::ShootingSound($gunType), 60);
        GiveReactService::execute($player, $reaction);
    }
}