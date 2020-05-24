<?php


namespace gun_system\interpreter;


use Closure;
use gun_system\client\GunClient;
use gun_system\controller\gun_controllers\ClipReloadingController;
use gun_system\controller\gun_controllers\MagazineReloadingController;
use gun_system\controller\gun_controllers\OneByOneReloadingController;
use gun_system\controller\gun_controllers\OverheatController;
use gun_system\controller\gun_controllers\ShootingController;
use gun_system\controller\sounds_controllers\OtherGunSoundsController;
use gun_system\models\ClipReloadingType;
use gun_system\models\Gun;
use gun_system\models\GunPrecision;
use gun_system\models\GunType;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OneByOneReloadingType;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\TextFormat;

abstract class GunInterpreter
{
    /**
     * @var GunClient
     */
    protected $client;

    /**
     * @var TaskScheduler
     */
    protected $scheduler;

    /**
     * @var Player
     */
    protected $owner;
    /**
     * @var Gun
     */
    protected $gun;
    /**
     * @var ClipReloadingController|MagazineReloadingController|OneByOneReloadingController
     */
    protected $reloadingController;
    /**
     * @var ShootingController
     */
    protected $shootingController;
    /**
     * @var OverheatController
     */
    protected $overheatController;

    private $isADS;

    public function __construct(Gun $gun, Player $owner, TaskScheduler $scheduler) {
        $this->scheduler = $scheduler;
        $this->owner = $owner;
        $this->gun = $gun;
        $reloadingType = $this->gun->getReloadingType();

        if ($reloadingType instanceof MagazineReloadingType) {
            $this->reloadingController = new MagazineReloadingController($this->owner, $reloadingType->magazineCapacity, $reloadingType->second);
        } else if ($reloadingType instanceof ClipReloadingType) {
            $this->reloadingController = new ClipReloadingController($this->owner, $reloadingType->magazineCapacity, $reloadingType->clipCapacity, $reloadingType->secondOfClip, $reloadingType->secondOfOne);
        } else if ($reloadingType instanceof OneByOneReloadingType) {
            $this->reloadingController = new OneByOneReloadingController($this->owner, $reloadingType->magazineCapacity, $reloadingType->second);
        }

        $this->shootingController = new ShootingController($gun->getType(), $gun->getRate(), function ($value): int {
            $this->reloadingController->currentBullet -= $value;
            return $this->reloadingController->currentBullet;
        }, $scheduler);

        $this->overheatController = new OverheatController(
            $gun->getOverheatRate(),
            function () {
                $this->cancelShooting();
                OtherGunSoundsController::LMGOverheat()->play($this->owner);
                $this->owner->sendPopup("オーバーヒート");
            },
            function () {
                OtherGunSoundsController::LMGReady()->play($this->owner);
                $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
            },
            $this->scheduler);

        $this->client = new GunClient($this->owner, $this->gun);
    }

    public function setWhenBecomeReady(Closure $whenBecomeReady): void {
        $this->shootingController->whenBecomeReady = $whenBecomeReady;
    }

    public function scare(Closure $onFinished): void {
        $this->gun->setPrecision(new GunPrecision($this->gun->getPrecision()->getADS() - 3, $this->gun->getPrecision()->getHipShooting() - 3));
        $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick): void {
            $this->gun->setPrecision(new GunPrecision($this->gun->getPrecision()->getADS() + 3, $this->gun->getPrecision()->getHipShooting() + 3));
        }), 20 * 3);
        $this->client->scare($this->scheduler, $onFinished);
    }

    public function cancelShooting(): void {
        $this->shootingController->cancelShooting();
    }

    public function cancelReloading(): void {
        $this->reloadingController->cancelReloading();
    }

    public function tryShootOnce() {
        if ($this->reloadingController->isCancelable())
            $this->reloadingController->cancelReloading();

        if ($this->reloadingController->isReloading()) {
            $this->owner->sendPopup("リロード中");
            return;
        }

        if ($this->reloadingController->isEmpty()) {
            $this->tryReload();
            return;
        }

        if ($this->reloadingController->isReloading()) {
            $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->shootingController->onCoolTime()) {
            return;
        }

        if ($this->overheatController->isOverheat()) {
            $this->owner->sendPopup("オーバーヒート中");
            return;
        }

        $this->shootingController->shootOnce(function (): void {
            $this->overheatController->raise();
            $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
            $this->client->shoot($this->reloadingController->currentBullet, $this->reloadingController->magazineCapacity, $this->scheduler);
        });
    }

    public function tryShoot(): void {
        if ($this->reloadingController->isCancelable())
            $this->reloadingController->cancelReloading();

        if ($this->reloadingController->isReloading()) {
            $this->owner->sendPopup("リロード中");
            return;
        }

        if ($this->reloadingController->isEmpty()) {
            $this->tryReload();
            return;
        }

        if ($this->overheatController->isOverheat()) {
            $this->owner->sendPopup("オーバーヒート中");
            return;
        }

        if ($this->shootingController->onCoolTime()) {
            //TODO: 1/rate - (now-lastShootDate)
            $this->shootingController->delayShoot(1 / $this->gun->getRate()->getPerSecond(), function (): void {
                $this->client->shoot($this->reloadingController->currentBullet, $this->reloadingController->magazineCapacity, $this->scheduler);
            });
            $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
            return;
        }

        if ($this->gun->getType()->equal(GunType::LMG()) && !$this->owner->isSneaking()) {
            $this->shootingController->delayShoot(1 / $this->gun->getRate()->getPerSecond(), function (): void {
                $this->client->shoot($this->reloadingController->currentBullet, $this->reloadingController->magazineCapacity, $this->scheduler);
            });
        }
        $this->shootingController->shoot(function (): void {
            $this->overheatController->raise();
            $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
            $this->client->shoot($this->reloadingController->currentBullet, $this->reloadingController->magazineCapacity, $this->scheduler);
        });
    }

    public function tryReload(): void {
        if ($this->reloadingController->isReloading()) {
            $this->owner->sendPopup("リロード中");
            return;
        }

        if ($this->reloadingController->isFull()) {
            $this->owner->sendPopup("Max");
            return;
        }

        if ($this->gun->getRemainingAmmo() === 0) {
            $this->owner->sendPopup("弾薬がありません");
            return;
        }

        if ($this->overheatController->isOverheat()) {
            $this->owner->sendPopup("オーバーヒート中");
            return;
        }

        $this->cancelShooting();

        $this->owner->sendPopup("リロード");
        $reduceBulletFunc = function ($value): int {
            $this->gun->setRemainingAmmo($this->gun->getRemainingAmmo()-$value);
            return $this->gun->getRemainingAmmo();
        };

        $onFinishedReloading = function (): void {
            $this->owner->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $this->reloadingController->currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE  . $this->gun->getRemainingAmmo());
        };

        $this->reloadingController->carryOut($this->scheduler, $this->gun->getRemainingAmmo(), $reduceBulletFunc, $onFinishedReloading);
    }

    /**
     * @return Gun
     */
    public function getGunData(): Gun {
        return $this->gun;
    }

}