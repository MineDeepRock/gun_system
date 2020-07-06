<?php


namespace gun_system\controller;


use Closure;
use gun_system\pmmp\sounds\ReloadingSounds;
use gun_system\pmmp\service\PlaySoundsService;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class OneByOneReloadingController extends ReloadingController
{
    private $oneReloadTaskHandler;

    public function cancel(): void {
        if ($this->oneReloadTaskHandler !== null)
            $this->oneReloadTaskHandler->cancel();
        $this->onReloading = false;
    }

    public function execute(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount, Closure $onSucceed): void {
        $this->onReloading = true;

        $this->oneReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(function (int $currentTick) use ($player, $inventoryAmmoAmount, $onSucceed): void {
            PlaySoundsService::play($player, ReloadingSounds::ReloadOne());
            $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() + 1);

            $inventoryAmmoAmount = $onSucceed(1);
            if ($inventoryAmmoAmount === 0 || $this->magazineData->getCurrentAmmo() === $this->magazineData->getCapacity()) {
                $this->oneReloadTaskHandler->cancel();
                $this->onReloading = false;
            }
        }), 20 * $this->reloadingData->getSecondOfOne(), 20 * $this->reloadingData->getSecondOfOne());
    }
}