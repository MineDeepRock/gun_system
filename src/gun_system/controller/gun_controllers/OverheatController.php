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

    private $handler;

    public function __construct(OverheatRate $rate, Closure $onOverheat, Closure $onFinished, TaskScheduler $scheduler) {
        $this->rate = $rate;
        $this->scheduler = $scheduler;
        $this->onOverheat = $onOverheat;
        $this->onFinished = $onFinished;
        $this->isOverheat = false;
    }

    public function raise(): void {
        if ($this->handler !== null) {
            if (!$this->handler->isCancelled()) {
                $this->handler->cancel();
            }
        }

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

        $this->handler = $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            $this->down(100);
            var_dump("down");
        }), 20 * 3);
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
