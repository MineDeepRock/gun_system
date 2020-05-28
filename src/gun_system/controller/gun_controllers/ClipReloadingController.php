<?php


namespace gun_system\controller\gun_controllers;


use Closure;
use gun_system\controller\sounds_controllers\ReloadSoundsController;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class ClipReloadingController extends ReloadingController
{
    private $clipCapacity;
    private $secondOfClip;
    private $secondOfOne;
    private $clipReloadTaskHandler;
    private $oneReloadTaskHandler;

    public function __construct(Player $owner, int $magazineCapacity, int $clipCapacity, float $secondOfClip, float $secondOfOne) {
        parent::__construct($owner, $magazineCapacity);
        $this->clipCapacity = $clipCapacity;
        $this->secondOfClip = $secondOfClip;
        $this->secondOfOne = $secondOfOne;
    }

    public function cancelReloading(): void {
        if ($this->clipReloadTaskHandler !== null)
            $this->clipReloadTaskHandler->cancel();
        if ($this->oneReloadTaskHandler !== null)
            $this->oneReloadTaskHandler->cancel();
        $this->onReloading = false;
    }

    function carryOut(TaskScheduler $scheduler, int $inventoryBullets, Closure $reduceBulletFunc, Closure $onFinished): void {
        $emptySlot = $this->magazineCapacity - $this->currentBullet;
        $this->onReloading = true;

        if ($inventoryBullets >= $this->clipCapacity && $emptySlot >= $this->clipCapacity) {
            $inventoryBullets = $reduceBulletFunc($this->clipCapacity);

            ReloadSoundsController::ClipPush()->play($this->owner);
            $this->clipReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(function (int $currentTick) use ($scheduler, $inventoryBullets, $reduceBulletFunc, $onFinished): void {
                $this->currentBullet += $this->clipCapacity;
                $onFinished();
                ReloadSoundsController::ClipPing()->play($this->owner);
                if ($inventoryBullets < $this->clipCapacity) {
                    $this->clipReloadTaskHandler->cancel();
                    $this->reloadOneByOne($scheduler, $inventoryBullets, $reduceBulletFunc, $onFinished);
                }
                if (($this->magazineCapacity - $this->currentBullet) < $this->clipCapacity) {
                    $this->clipReloadTaskHandler->cancel();
                    $this->reloadOneByOne($scheduler, $inventoryBullets, $reduceBulletFunc, $onFinished);
                }
            }), 20 * $this->secondOfClip, 20 * $this->secondOfClip);
        } else {
            $this->reloadOneByOne($scheduler, $inventoryBullets, $reduceBulletFunc, $onFinished);
        }

    }

    private function reloadOneByOne(TaskScheduler $scheduler, int $inventoryBullets, Closure $reduceBulletFunc, Closure $onFinished) {
        if ($this->currentBullet !== $this->magazineCapacity) {
            $this->oneReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(function (int $currentTick) use ($inventoryBullets, $reduceBulletFunc, $onFinished): void {
                ReloadSoundsController::ReloadOne()->play($this->owner);
                $this->currentBullet++;
                $inventoryBullets = $reduceBulletFunc(1);
                $onFinished();
                if ($inventoryBullets === 0 || $this->currentBullet === $this->magazineCapacity) {
                    $this->oneReloadTaskHandler->cancel();
                    $this->onReloading = false;
                }
            }), 20 * $this->secondOfOne, 20 * $this->secondOfOne);
        } else {
            $this->onReloading = false;
        }
    }

    function isCancelable(): bool {
        return true;
    }
}
