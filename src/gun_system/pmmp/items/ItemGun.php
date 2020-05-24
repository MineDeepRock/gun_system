<?php


namespace gun_system\pmmp\items;


use Closure;
use gun_system\interpreter\GunInterpreter;
use gun_system\models\Gun;
use pocketmine\item\ItemIds;
use pocketmine\item\Tool;
use pocketmine\Player;

abstract class
ItemGun extends Tool
{
    protected $gunInterpreter;

    public function __construct(string $name, GunInterpreter $gunInterpreter) {
        $this->gunInterpreter = $gunInterpreter;
        parent::__construct(ItemIds::BOW, 0, $name);
        $this->setUnbreakable(true);
    }

    public function getMaxDurability(): int {
        return 100;
    }

    public function onReleaseUsing(Player $player): bool {
        $this->gunInterpreter->cancelShooting();
        $player->getInventory()->sendContents($player);
        return false;
    }

    public function shoot(): void {
        $this->gunInterpreter->tryShoot();
    }

    public function shootOnce(): void {
        $this->gunInterpreter->tryShootOnce();
    }

    public function reload(): void {
        $this->gunInterpreter->tryReload();
    }

    public function cancelReloading(): void {
        $this->gunInterpreter->cancelReloading();
    }

    public function scare(Closure $onFinished): void {
        $this->gunInterpreter->scare($onFinished);
    }

    public function getGunData(): Gun {
        return $this->gunInterpreter->getGunData();
    }

    public function getInterpreter(): GunInterpreter {
        return $this->gunInterpreter;
    }
}