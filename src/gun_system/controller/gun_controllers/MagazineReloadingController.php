<?php


namespace gun_system\controller\gun_controllers;

use Closure;
use gun_system\controller\sounds_controllers\ReloadSoundsController;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class MagazineReloadingController extends ReloadingController
{
    private $second;
    private $handler;

    public function __construct(Player $owner, int $magazineCapacity, float $second) {
        parent::__construct($owner, $magazineCapacity);
        $this->second = $second;
    }

    public function cancelReloading() {
        if ($this->handler !== null)
            $this->handler->cancel();
        $this->onReloading = false;
    }

    function carryOut(TaskScheduler $scheduler, int $inventoryBullets, Closure $reduceBulletFunc, Closure $onFinished): void {
        $this->onReloading = true;
        $empty = $this->magazineCapacity - $this->currentBullet;

        ReloadSoundsController::MagazineOut()->play($this->owner);
        $this->handler = $scheduler->scheduleDelayedTask(new ClosureTask(
            function (int $currentTick) use ($empty, $inventoryBullets, $onFinished, $reduceBulletFunc): void {
                if ($empty > $inventoryBullets) {
                    $this->currentBullet += $inventoryBullets;
                    $reduceBulletFunc($inventoryBullets);
                } else {
                    $this->currentBullet += $empty;
                    $reduceBulletFunc($empty);
                }
                $this->onReloading = false;
                $onFinished();
                ReloadSoundsController::MagazineIn()->play($this->owner);
            }
        ), 20 * $this->second);
    }

    function isCancelable(): bool {
        return false;
    }
}