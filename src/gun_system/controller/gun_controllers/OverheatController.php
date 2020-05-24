<?php


namespace gun_system\controller\gun_controllers;


use Closure;
use gun_system\models\OverheatRate;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class OverheatController
{
    private $rate;
    private $gauge;
    private $isOverheat;

    private $scheduler;

    private $onOverheat;
    private $onFinished;

    public function __construct(OverheatRate $rate, Closure $onOverheat, Closure $onFinished, TaskScheduler $scheduler) {
        $this->rate = $rate;
        $this->scheduler = $scheduler;
        $this->onOverheat = $onOverheat;
        $this->onFinished = $onFinished;
        $this->isOverheat = false;

        if ($this->rate->getPerShoot() !== 0) {
            $this->scheduler->scheduleRepeatingTask(new ClosureTask(function (int $currentTick): void {
                $this->down(10);
            }), 20 * 1);
        }
    }

    public function raise(): void {
        if ($this->rate->getPerShoot() === 0)
            return;

        $this->gauge += $this->rate->getPerShoot();
        if ($this->gauge >= 100) {
            $this->isOverheat = true;
            ($this->onOverheat)();

            $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
                $this->isOverheat = false;
                ($this->onFinished)();
                $this->reset();
            }), 20 * 2);
        }
    }

    public function down(int $value): void {
        if ($this->rate->getPerShoot() === 0)
            return;

        $this->gauge -= $value;
        if ($this->gauge < 0)
            $this->gauge = 0;
    }

    public function reset(): void {
        if ($this->rate->getPerShoot() === 0)
            return;
        $this->gauge = 0;
    }

    /**
     * @return bool
     */
    public function isOverheat(): bool {
        return $this->isOverheat;
    }
}
