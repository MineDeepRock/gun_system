<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\reloading\Magazine;
use gun_system\model\reloading\MagazineData;
use gun_system\pmmp\sounds\ReloadingSounds;
use gun_system\pmmp\service\PlaySoundsService;
use gun_system\pmmp\service\SendMessageService;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class MagazineReloadingController extends ReloadingController
{
    protected $isCancelable = true;

    private $handler;
    private $messageHandler;
    private $reloadingProgress;

    public function __construct(MagazineData $magazineData, Magazine $reloadingData) {
        parent::__construct($magazineData, $reloadingData);
    }

    public function cancel(): void {
        if ($this->handler !== null) {
            $this->handler->cancel();
            $this->messageHandler->cancel();
        }
        $this->reloadingProgress = 0;
        $this->onReloading = false;
    }

    function execute(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount, Closure $onSucceed): void {
        if ($this->reloadingData instanceof Magazine) {
            $this->messageHandler = $scheduler->scheduleRepeatingTask(new ClosureTask(function (int $tick) use ($player): void {
                $this->reloadingProgress += 0.1;
                SendMessageService::sendReloadingProgress($player, $this->magazineData->getCapacity(), $this->reloadingProgress, $this->reloadingData->getSecond());
            }), 20 * 0.1);

            $this->onReloading = true;
            $empty = $this->magazineData->getCapacity() - $this->magazineData->getCurrentAmmo();

            PlaySoundsService::play($player, ReloadingSounds::MagazineOut());
            $this->handler = $scheduler->scheduleDelayedTask(new ClosureTask(
                function (int $currentTick) use ($player, $empty, $inventoryAmmoAmount, $onSucceed): void {
                    if ($empty > $inventoryAmmoAmount) {
                        $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() + $inventoryAmmoAmount);
                        $onSucceed($inventoryAmmoAmount);
                    } else {
                        $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() + $empty);
                        $onSucceed($empty);
                    }
                    $this->onReloading = false;
                    PlaySoundsService::play($player, ReloadingSounds::MagazineIn());
                    $this->reloadingProgress = 0;
                    $this->messageHandler->cancel();
                }
            ), 20 * $this->reloadingData->getSecond());
        }
    }
}