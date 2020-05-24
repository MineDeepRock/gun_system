<?php


namespace gun_system\controller\gun_controllers;



use Closure;
use gun_system\controller\sounds_controllers\ReloadSoundsController;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class OneByOneReloadingController extends ReloadingController
{
    private $second;
    private $oneReloadTaskHandler;

    public function __construct(Player $owner, int $magazineCapacity, float $second) {
        parent::__construct($owner, $magazineCapacity);
        $this->second = $second;
    }

    public function cancelReloading(): void {
        if ($this->oneReloadTaskHandler !== null)
            $this->oneReloadTaskHandler->cancel();
        $this->onReloading = false;
    }

    function carryOut(TaskScheduler $scheduler, int $inventoryBullets, Closure $reduceBulletFunc, Closure $onFinished): void {
        $this->onReloading = true;

        $this->oneReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(function (int $currentTick) use ($inventoryBullets, $reduceBulletFunc, $onFinished): void {
            ReloadSoundsController::ReloadOne()->play($this->owner);
            $this->currentBullet++;
            $inventoryBullets = $reduceBulletFunc(1);
            $onFinished();
            if ($inventoryBullets === 0)
                $this->oneReloadTaskHandler->cancel();
            if ($this->currentBullet === $this->magazineCapacity)
                $this->oneReloadTaskHandler->cancel();
        }), 20 * $this->second, 20 * $this->second);
    }

    function toString(): string {
        return strval($this->second);
    }

    function isCancelable(): bool {
        return true;
    }
}