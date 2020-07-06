<?php


namespace gun_system\pmmp\service;


use gun_system\model\performance\Reaction;
use pocketmine\math\Vector3;
use pocketmine\Player;

class GiveReactService
{
    static function execute(Player $player, Reaction $reaction) {
        if ($reaction !== 0.0 && !$player->isSneaking()) {
            $playerPosition = $player->getLocation();
            $dir = -$playerPosition->getYaw() - 90.0;
            $pitch = -$playerPosition->getPitch() - 180.0;
            $xd = $reaction * $reaction * cos(deg2rad($dir)) * cos(deg2rad($pitch)) / 6;
            $zd = $reaction * $reaction * -sin(deg2rad($dir)) * cos(deg2rad($pitch)) / 6;

            $vec = new Vector3($xd, 0, $zd);
            $vec->multiply(3);
            $player->setMotion($vec);
        }
    }
}