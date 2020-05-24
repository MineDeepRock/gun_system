<?php


namespace gun_system\controller\gun_controllers;


use Closure;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

abstract class ReloadingController
{
    public $magazineCapacity;
    public $currentBullet;
    protected $onReloading;

    //TODO:消したい
    protected $owner;

    public function __construct(Player $owner, int $magazineCapacity) {
        $this->onReloading = false;
        $this->magazineCapacity = $magazineCapacity;
        $this->currentBullet = $magazineCapacity;

        $this->owner = $owner;
    }

    abstract function isCancelable(): bool;

    abstract function carryOut(TaskScheduler $scheduler, int $inventoryBullets, Closure $reduceBulletFunc, Closure $onFinished): void;

    public function isFull(): bool {
        return $this->currentBullet === $this->magazineCapacity;
    }

    public function isEmpty(): bool {
        return $this->currentBullet === 0;
    }

    /**
     * @return bool
     */
    public function isReloading(): bool {
        return $this->onReloading;
    }
}