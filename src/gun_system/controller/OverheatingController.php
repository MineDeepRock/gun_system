<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\performance\OverheatingRate;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class OverheatingController
{
    private $gauge = 0;
    private $isOverheat = false;

    private $onOverheat;
    private $onFinished;

    private $scheduler;
    private $handler;

    public function __construct(TaskScheduler $scheduler, Closure $onOverheat, Closure $onFinished) {
        $this->onOverheat = $onOverheat;
        $this->onFinished = $onFinished;

        $this->scheduler = $scheduler;
    }

    public function raise(OverheatingRate $rate, Player $player): void {
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
            ($this->onOverheat)($player);

            $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($player): void {
                if ($player->isOnline()) {
                    $this->isOverheat = false;
                    ($this->onFinished)($player);
                    $this->reset();
                }
            }), 20 * 3);
        }

        $this->handler = $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            $this->down(50);
        }), 20 * 2);
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