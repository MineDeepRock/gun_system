<?php


namespace gun_system\model\reloading;


use Closure;
use gun_system\model\Magazine;
use gun_system\model\reloading_data\ReloadingData;
use pocketmine\player\Player;
use pocketmine\scheduler\TaskScheduler;

abstract class Reloading
{
    protected $onReloading;
    /**
     * @var Magazine
     */
    protected $magazineData;
    /**
     * @var ReloadingData
     */
    protected $reloadingData;

    public function __construct(Magazine $magazineData, ReloadingData $reloadingData) {
        $this->onReloading = false;
        $this->magazineData = $magazineData;
        $this->reloadingData = $reloadingData;
    }

    abstract function execute(Player $player, TaskScheduler $scheduler, int $inventoryAmmoAmount, Closure $onSucceed): void;

    abstract function cancel(): void;

    /**
     * @return bool
     */
    public function isReloading(): bool {
        return $this->onReloading;
    }
}