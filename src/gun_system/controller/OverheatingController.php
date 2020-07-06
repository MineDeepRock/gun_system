<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\performance\OverheatingRate;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class OverheatingController
{
    private $gauge;
    private $isOverheat;

    private $onOverheat;
    private $onFinished;

    private $scheduler;
    private $handler;

    public function __construct(TaskScheduler $scheduler, Closure $onOverheat, Closure $onFinished) {
        $this->onOverheat = $onOverheat;
        $this->onFinished = $onFinished;
        $this->isOverheat = false;

        $this->scheduler = $scheduler;
    }

    public function raise(OverheatingRate $rate): void {
        if ($this->handler !== null) {
            if (!$this->handler->isCancelled()) {
                $this->handler->cancel();
            }
        }

        if ($rate->getPerShoot() === 0)
            return;

        $this->gauge += $rate->getPerShoot();
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
        }), 20 * 3);
    }

    public function down(int $value): void {
        $this->gauge -= $value;
        if ($this->gauge < 0)
            $this->gauge = 0;
    }

    public function reset(): void {
        $this->gauge = 0;
    }

    /**
     * @return bool
     */
    public function isOverheat(): bool {
        return $this->isOverheat;
    }
}