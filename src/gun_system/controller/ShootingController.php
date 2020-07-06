<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\GunType;
use gun_system\model\performance\FiringRate;
use gun_system\model\reloading\MagazineData;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class ShootingController
{
    private $gunType;
    private $rate;
    private $magazineData;


    private $onCoolTime;
    private $isShooting;

    private $scheduler;
    private $shootingTaskHandler;
    private $delayShootingTaskHandler;

    public $becomeReadyCallBack;

    public function __construct(TaskScheduler $scheduler, GunType $gunType, FiringRate $rate, MagazineData $magazineData, Closure $becomeReadyCallBack = null) {
        $this->scheduler = $scheduler;

        $this->gunType = $gunType;
        $this->rate = $rate;
        $this->magazineData = $magazineData;

        $this->onCoolTime = false;
        $this->isShooting = false;

        $this->becomeReadyCallBack = $becomeReadyCallBack;
    }

    public function ontoCoolTime(): void {
        $this->onCoolTime = true;
        $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            if ($this->becomeReadyCallBack !== null)
                ($this->becomeReadyCallBack)();

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
            $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() - 1);
            $onSucceed();
        }
    }

    public function shoot(Closure $onSucceed): void {
        $this->isShooting = true;
        if ($this->shootingTaskHandler !== null)
            $this->shootingTaskHandler->cancel();
        if ($this->delayShootingTaskHandler !== null)
            $this->delayShootingTaskHandler->cancel();

        $this->shootingTaskHandler = $this->scheduler->scheduleRepeatingTask(
            new ClosureTask(function (int $currentTick) use ($onSucceed): void {
                //TODO:あんまりよくない
                $this->ontoCoolTime();
                $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() - 1);

                $onSucceed();
                if ($this->magazineData->getCurrentAmmo() === 0)
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