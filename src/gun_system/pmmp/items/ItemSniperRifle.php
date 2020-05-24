<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\SniperRifleInterpreter;
use pocketmine\Player;

class ItemSniperRifle extends ItemGun
{
    public function __construct(string $name, SniperRifleInterpreter $interpreter) {
        parent::__construct($name, $interpreter);
    }

    public function onReleaseUsing(Player $player): bool {
        $this->shootOnce();
        $player->getInventory()->sendContents($player);
        return true;
    }

    public function aim(): void {
        $this->gunInterpreter->aim();
    }
}