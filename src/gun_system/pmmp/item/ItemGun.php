<?php


namespace gun_system\pmmp\item;


use gun_system\controller\ClipReloadingController;
use gun_system\controller\MagazineReloadingController;
use gun_system\controller\OneByOneReloadingController;
use gun_system\controller\OverheatingController;
use gun_system\controller\ReloadingController;
use gun_system\controller\ShootingController;
use gun_system\model\Gun;
use gun_system\model\GunType;
use gun_system\model\reloading\Clip;
use gun_system\model\reloading\Magazine;
use gun_system\model\reloading\OneByOne;
use gun_system\pmmp\service\SendMessageService;
use gun_system\pmmp\service\ShootingService;
use pocketmine\item\ItemIds;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class ItemGun extends Tool
{
    private $scheduler;

    private $gun;

    /**
     * @var ReloadingController
     */
    private $reloadingController;

    /**
     * @var ShootingController
     */
    private $shootingController;

    /**
     * @var OverheatingController
     */
    private $overheatingController;

    public function __construct(Gun $gun, TaskScheduler $scheduler) {
        $this->gun = $gun;
        $this->scheduler = $scheduler;

        $reloadingData = $gun->getReloadingData();
        if ($reloadingData instanceof Magazine) {
            $this->reloadingController = new MagazineReloadingController($this->gun->getMagazineData(), $reloadingData);
        } else if ($reloadingData instanceof Clip) {
            $this->reloadingController = new ClipReloadingController($this->gun->getMagazineData(), $reloadingData);
        } else if ($reloadingData instanceof OneByOne) {
            $this->reloadingController = new OneByOneReloadingController($this->gun->getMagazineData(), $reloadingData);
        }

        $this->shootingController = new ShootingController($this->scheduler, $this->gun->getType(), $this->gun->getFiringRate(), $this->gun->getMagazineData());
        $this->overheatingController = new OverheatingController($this->scheduler,
            function () {
                //TODO
            },
            function () {
                //TODO
            });

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
            $this->shoot($player);
        } else {
            $this->shootingController->cancelShooting();
            $player->getInventory()->sendContents($player);
        }
        return false;
    }

    public function shoot(Player $player): void {
        if ($this->reloadingController->isReloading()) {
            if ($this->reloadingController->isCancelable()) {
                $this->reloadingController->cancel();
            } else {
                SendMessageService::sendReloadingMessage($player);
                return;
            }
        }

        if ($this->gun->getMagazineData()->isEmpty()) {
            $this->reload($player);
            return;
        }

        if ($this->overheatingController->isOverheat()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        if ($this->shootingController->onCoolTime()) {
            //TODO: 1/rate - (now-lastShootDate)
            $this->shootingController->delayShoot(1 / $this->gun->getFiringRate()->getPerSecond(), function () use ($player): void {
                ShootingService::execute($this->scheduler, $player, $this->gun);
            });
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->gun->getType()->equals(GunType::LMG()) && !$player->isSneaking()) {
            $this->shootingController->delayShoot(1 / $this->gun->getFiringRate()->getPerSecond(), function () use ($player): void {
                ShootingService::execute($this->scheduler, $player, $this->gun);
            });
        }

        $this->shootingController->shoot(function () use ($player): void {
            ShootingService::execute($this->scheduler, $player, $this->gun);
            $this->overheatingController->raise($this->gun->getOverheatRate());
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
        });
    }

    public function shootOnce(Player $player): void {
        if ($this->reloadingController->isReloading()) {
            if ($this->reloadingController->isCancelable()) {
                $this->reloadingController->cancel();
            } else {
                SendMessageService::sendReloadingMessage($player);
                return;
            }
        }

        if ($this->gun->getMagazineData()->isEmpty()) {
            $this->reload($player);
            return;
        }

        if ($this->shootingController->onCoolTime()) {
            return;
        }

        if ($this->overheatingController->isOverheat()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        $this->shootingController->shootOnce(function () use ($player): void {
            ShootingService::execute($this->scheduler, $player, $this->gun);
            $this->overheatingController->raise($this->gun->getOverheatRate());
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
        });
    }

    public function reload($player): void {
        if ($this->reloadingController->isReloading()) {
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

        if ($this->overheatingController->isOverheat()) {
            SendMessageService::sendOverheatingMessage($player);
            return;
        }

        $this->shootingController->cancelShooting();

        $reduceBulletFunc = function ($value) use ($player): int {
            $this->gun->setRemainingAmmo($this->gun->getRemainingAmmo() - $value);
            SendMessageService::sendBulletCount($player, $this->gun->getMagazineData()->getCurrentAmmo(), $this->gun->getRemainingAmmo());
            return $this->gun->getRemainingAmmo();
        };

        $this->reloadingController->execute($player, $this->scheduler, $this->gun->getRemainingAmmo(), $reduceBulletFunc);

    }

    public function cancelReloading(): void {
        $this->reloadingController->cancel();
    }

    /**
     * @return Gun
     */
    public function getGun(): Gun {
        return $this->gun;
    }
}