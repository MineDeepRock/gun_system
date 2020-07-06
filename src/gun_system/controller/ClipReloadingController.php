<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\reloading\Clip;
use gun_system\model\reloading\MagazineData;
use gun_system\pmmp\sounds\ReloadingSounds;
use gun_system\pmmp\service\PlaySoundsService;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class ClipReloadingController extends ReloadingController
{
    private $clipReloadTaskHandler;
    private $oneReloadTaskHandler;
    /**
     * @var Clip
     */
    protected $reloadingData;

    public function __construct(MagazineData $magazineData, Clip $reloadingData) {
        parent::__construct($magazineData, $reloadingData);
    }

    public function cancel(): void {
        if ($this->clipReloadTaskHandler !== null)
            $this->clipReloadTaskHandler->cancel();
        if ($this->oneReloadTaskHandler !== null)
            $this->oneReloadTaskHandler->cancel();

        $this->onReloading = false;
    }

    public function execute(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount, Closure $onSucceed): void {
        $emptySlot = $this->magazineData->getCapacity() - $this->magazineData->getCurrentAmmo();
        $this->onReloading = true;

        if ($inventoryAmmoAmount >= $this->magazineData->getCapacity() && $emptySlot >= $this->reloadingData->getClipCapacity()) {

            PlaySoundsService::play($player, ReloadingSounds::ClipPush());
            $this->clipReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(
                new ClosureTask(function (int $currentTick) use ($player, $scheduler, $inventoryAmmoAmount, $onSucceed): void {
                    $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() + $this->reloadingData->getClipCapacity());

                    $inventoryAmmoAmount = $onSucceed($this->reloadingData->getClipCapacity());

                    PlaySoundsService::play($player, ReloadingSounds::ClipPing());

                    if ($inventoryAmmoAmount < $this->magazineData->getCapacity()) {
                        $this->clipReloadTaskHandler->cancel();
                        $this->reloadOneByOne($player, $scheduler, $inventoryAmmoAmount, $onSucceed);
                    } else if (($this->magazineData->getCapacity() - $this->magazineData->getCurrentAmmo()) < $this->reloadingData->getClipCapacity()) {
                        $this->clipReloadTaskHandler->cancel();
                        $this->reloadOneByOne($player, $scheduler, $inventoryAmmoAmount, $onSucceed);
                    }
                }), 20 * $this->reloadingData->getSecondOfClip(), 20 * $this->reloadingData->getSecondOfClip());
        } else {
            $this->reloadOneByOne($player, $scheduler, $inventoryAmmoAmount, $onSucceed);
        }
    }

    private function reloadOneByOne(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount, Closure $onSucceed) {
        if ($this->magazineData->getCurrentAmmo() !== $this->magazineData->getCapacity()) {

            $this->oneReloadTaskHandler = $scheduler->scheduleDelayedRepeatingTask(
                new ClosureTask(function (int $currentTick) use ($player, $inventoryAmmoAmount, $onSucceed): void {
                    PlaySoundsService::play($player, ReloadingSounds::ReloadOne());

                    $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() + 1);

                    $inventoryAmmoAmount = $onSucceed(1);

                    if ($inventoryAmmoAmount === 0 || $this->magazineData->getCurrentAmmo() === $this->magazineData->getCapacity()) {
                        $this->oneReloadTaskHandler->cancel();
                        $this->onReloading = false;
                    }
                }), 20 * $this->reloadingData->getSecondOfOne(), 20 * $this->reloadingData->getSecondOfOne());
        } else {
            $this->onReloading = false;
        }
    }
}