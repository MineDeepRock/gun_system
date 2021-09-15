<?php


namespace gun_system\service;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;

class GiveScareService
{
    static function execute(Player $player, int $tick, int $level) {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), $tick, $level));
    }
}