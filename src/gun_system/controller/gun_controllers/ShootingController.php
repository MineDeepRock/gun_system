<?php


namespace gun_system\controller\gun_controllers;


use Closure;
use gun_system\models\GunRate;
use gun_system\models\GunType;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class ShootingController
{
    private $gunType;
    private $rate;
    private $reduceBulletFunc;

    private $onCoolTime;
    private $isShooting;

    public $whenBecomeReady;

    private $scheduler;
    private $shootingTaskHandler;
    private $delayShootingTaskHandler;

    public function __construct(GunType $gunType, GunRate $rate, Closure $reduceBulletFunc, TaskScheduler $scheduler) {
        $this->rate = $rate;
        $this->reduceBulletFunc = $reduceBulletFunc;

        $this->gunType = $gunType;

        $this->scheduler = $scheduler;
        $this->onCoolTime = false;
    }

    public function ontoCoolTime(): void {
        $this->onCoolTime = true;
        $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            if ($this->whenBecomeReady !== null)
                ($this->whenBecomeReady)();

            $this->onCoolTime = false;
        }), 20 * 1 / $this->rate->getPerSecond());
    }

    public function cancelShooting(): void {
        $this->isShooting = false;
        if ($this->shootingTaskHandler !== null)
            $this->shootingTaskHandler->cancel();
        if ($this->delayShootingTaskHandler !== null)
            $this->delayShootingTaskHandler->cancel();
    }

    public function delayShoot(int $second, Closure $onSucceed) {
        $this->delayShootingTaskHandler = $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($onSucceed) : void {
            $this->shoot($onSucceed);
        }), 20 * $second);
    }


    public function shootOnce(Closure $onSucceed): void {
        $this->ontoCoolTime();
        if (!$this->isShooting) {
            ($this->reduceBulletFunc)(1);
            $onSucceed();
        }
    }

    public function shoot(Closure $onSucceed): void {
        $this->isShooting = true;
        if ($this->shootingTaskHandler !== null)
            $this->shootingTaskHandler->cancel();
        if ($this->delayShootingTaskHandler !== null)
            $this->delayShootingTaskHandler->cancel();

        $this->shootingTaskHandler = $this->scheduler->scheduleRepeatingTask(new ClosureTask(function (int $currentTick) use ($onSucceed): void {
            //TODO:あんまりよくない
            $this->ontoCoolTime();
            $currentBullet = ($this->reduceBulletFunc)(1);
            $onSucceed();
            if ($currentBullet === 0)
                $this->cancelShooting();
        }), 20 * (1 / $this->rate->getPerSecond()));
    }

    /**
     * @return bool
     */
    public function onCoolTime(): bool {
        return $this->onCoolTime;
    }
}