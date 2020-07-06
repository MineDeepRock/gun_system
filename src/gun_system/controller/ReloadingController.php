<?php


namespace gun_system\controller;


use Closure;
use gun_system\model\reloading\MagazineData;
use gun_system\model\reloading\ReloadingData;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

abstract class ReloadingController
{
    protected $onReloading;
    protected $isCancelable;
    /**
     * @var MagazineData
     */
    protected $magazineData;
    /**
     * @var ReloadingData
     */
    protected $reloadingData;

    public function __construct(MagazineData $magazineData, ReloadingData $reloadingData) {
        $this->onReloading = false;
        $this->magazineData = $magazineData;
        $this->reloadingData = $reloadingData;
    }

    abstract function execute(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount,Closure $onSucceed): void;

    abstract function cancel(): void;

    /**
     * @return bool
     */
    public function isReloading(): bool {
        return $this->onReloading;
    }

    /**
     * @return bool
     */
    public function isCancelable(): bool {
        return $this->isCancelable;
    }
}