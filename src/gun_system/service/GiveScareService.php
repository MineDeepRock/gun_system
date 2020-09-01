<?php


namespace gun_system\service;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;

class GiveScareService
{
    static function execute(Player $player, int $tick, int $level) {
        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), $tick, $level));
    }
}