<?php


namespace gun_system\service;


use Closure;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class GiveScareService
{
    static function execute(TaskScheduler $scheduler) {
        $scheduler->scheduleDelayedTask(new ClosureTask(function (int $tick) : void {
            if ($this->owner->isOnline()) {
                //TODO: イベントを送る
            }
        }), 20 * 5);
    }
}