<?php


namespace gun_system\service;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class GiveScareService
{
    static function execute(TaskScheduler $scheduler,Player $player) {
        $scheduler->scheduleDelayedTask(new ClosureTask(function (int $tick) use ($player) : void {
            if ($player->isOnline()) {
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 5, 1));
            }
        }), 20 * 5);
    }
}