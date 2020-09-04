<?php


namespace gun_system\model;


use Closure;
use gun_system\model\performance\OverheatingRate;
use gun_system\pmmp\service\PlaySoundsService;
use gun_system\pmmp\sounds\OtherGunSounds;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class Overheat
{
    private $gauge;
    private $rate;
    private $isOverheated;

    private $scheduler;
    private $handler;


    private $onOverheated;
    private $onCooled;

    public function __construct(TaskScheduler $scheduler, OverheatingRate $rate, Closure $onOverheated, Closure $onCooled) {
        $this->gauge = 0;
        $this->rate = $rate;
        $this->isOverheated = false;
        $this->scheduler = $scheduler;
        $this->onOverheated = $onOverheated;
        $this->onCooled = $onCooled;
    }

    public function raise(OverheatingRate $rate, Player $player): void {
        if ($this->handler !== null) {
            if (!$this->handler->isCancelled()) {
                $this->handler->cancel();
            }
        }

        if ($rate->getPerShoot() === 0) return;

        $this->gauge += $rate->getPerShoot();
        if ($this->gauge >= 100) {
            $this->isOverheated = true;
            ($this->onOverheated)($player);

            $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($player): void {
                if ($player->isOnline()) {
                    $this->isOverheated = false;
                    $this->reset($player);
                }
            }), 20 * 3);
        }

        $this->handler = $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            $this->down(50);
        }), 20 * 2);
    }


    public function onOverheated(Player $player) {
        $player->sendTip("オーバーヒート");
        PlaySoundsService::playAround($player->getLevel(), $player->getPosition(), OtherGunSounds::LMGOverheat());
    }

    public function down(int $value): void {
        $this->gauge -= $value;
        if ($this->gauge < 0)
            $this->gauge = 0;
    }

    public function reset(Player $player): void {
        $this->gauge = 0;
        ($this->onCooled)($player);
    }

    /**
     * @return bool
     */
    public function isOverheated(): bool {
        return $this->isOverheated;
    }
}