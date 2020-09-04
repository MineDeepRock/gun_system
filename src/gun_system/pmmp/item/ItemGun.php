<?php


namespace gun_system\pmmp\item;


use gun_system\model\Gun;
use gun_system\model\GunType;
use gun_system\pmmp\service\SendMessageService;
use pocketmine\item\ItemIds;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class ItemGun extends Tool
{
    private $scheduler;

    private $gun;

    public function __construct(Gun $gun, TaskScheduler $scheduler) {
        $this->gun = $gun;
        $this->scheduler = $scheduler;

        parent::__construct(ItemIds::BOW, 0, $this->gun->getName());
        $this->setUnbreakable(true);
        $this->setCustomName($this->gun->getName());
    }

    public function getMaxDurability(): int {
        return 100;
    }

    public function aim(): void { }

    public function onReleaseUsing(Player $player): bool {
        if ($this->gun->getType()->equals(GunType::SniperRifle())) {
            $this->shootOnce($player);
            $player->getInventory()->sendContents($player);
        } else {
            $this->gun->cancelShooting();
            $player->getInventory()->sendContents($player);
        }
        return false;
    }

    public function shoot(Player $player): void {
        if ($this->gun->isReloading()) {
            if ($this->gun->getMagazineData()->isEmpty()) {
                $this->reload($player);
                return;
            }
            $this->gun->cancelReloading();
        }

        if ($this->gun->getMagazineData()->isEmpty()) {
            $this->reload($player);
            return;
        }

        if ($this->gun->isOverheated()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        if ($this->gun->onCoolTime()) {
            //TODO: 1/rate - (now-lastShootDate)
            $this->gun->delayShoot($player, 1 / $this->gun->getFiringRate()->getPerSecond());
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->gun->getType()->equals(GunType::LMG()) && !$player->isSneaking()) {
            $this->gun->delayShoot($player, 1 / $this->gun->getFiringRate()->getPerSecond());
        }

        $this->gun->shoot($player);
    }

    public function shootOnce(Player $player): void {
        if ($this->gun->isReloading()) {
            if ($this->gun->getMagazineData()->isEmpty()) {
                $this->reload($player);
                return;
            }
            $this->gun->cancelReloading();
        }

        if ($this->gun->getMagazineData()->isEmpty()) {
            $this->reload($player);
            return;
        }

        if ($this->gun->onCoolTime()) {
            return;
        }

        if ($this->gun->isOverheated()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        $this->gun->shootOnce($player);
    }

    public function reload($player): void {
        if ($this->gun->isReloading()) {
            SendMessageService::sendReloadingMessage($player);
            return;
        }

        if ($this->gun->getMagazineData()->isFull()) {
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->gun->getRemainingAmmo() === 0) {
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->gun->isOverheated()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        $this->gun->cancelShooting();

        $this->gun->reload($player, $this->gun->getRemainingAmmo());

    }

    public function cancelReloading(): void {
        $this->gun->cancelReloading();
    }

    /**
     * @return Gun
     */
    public function getGun(): Gun {
        return $this->gun;
    }
}