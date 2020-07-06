<?php


namespace gun_system\pmmp\item;


use pocketmine\Player;

class ItemSniperRifle extends ItemGun
{
    public function onReleaseUsing(Player $player): bool {
        $this->shootOnce($player);
        $player->getInventory()->sendContents($player);
        return true;
    }

    public function aim(): void {}
}