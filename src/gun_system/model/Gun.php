<?php


namespace gun_system\model;


use gun_system\model\attachment\Scope;
use gun_system\model\performance\AttackPoint;
use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\DamageGraph;
use gun_system\model\performance\FiringRate;
use gun_system\model\performance\OverheatingRate;
use gun_system\model\performance\Precision;
use gun_system\model\performance\Reaction;
use gun_system\model\reloading\ClipReloading;
use gun_system\model\reloading\MagazineReloading;
use gun_system\model\reloading\OneByOneReloading;
use gun_system\model\reloading_data\ClipReloadingData;
use gun_system\model\reloading_data\MagazineReloadingData;
use gun_system\model\reloading_data\OneByOneReloadingData;
use gun_system\model\reloading_data\ReloadingData;
use gun_system\pmmp\service\PlaySoundsService;
use gun_system\pmmp\service\SendMessageService;
use gun_system\pmmp\service\ShootingService;
use gun_system\pmmp\sounds\OtherGunSounds;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class Gun
{

    /**
     * @var TaskScheduler
     */
    private $scheduler;

    /**
     * @var GunType
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var AttackPoint
     */
    private $attackPoint;
    /**
     * @var FiringRate
     */
    private $firingRate;
    /**
     * @var BulletSpeed
     */
    private $bulletSpeed;
    /**
     * @var Reaction
     */
    private $reaction;
    /**
     * @var DamageGraph
     */
    private $damageGraph;
    /**
     * @var Precision
     */
    private $precision;
    /**
     * @var OverheatingRate
     */
    private $overheatRate;
    /**
     * @var Magazine
     */
    private $magazineData;
    /**
     * @var int
     */
    private $initialAmmo;
    /**
     * @var int
     */
    private $remainingAmmo;

    /**
     * @var ReloadingData
     */
    private $reloadingData;
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var Shooting
     */
    private $shooting;
    /**
     * @var Overheat
     */
    private $overheat;
    /**
     * @var ClipReloading|MagazineReloading|OneByOneReloading
     */
    private $reloading;

    public function __construct(TaskScheduler $taskScheduler, GunType $type, string $name, AttackPoint $attackPoint, FiringRate $firingRate, BulletSpeed $bulletSpeed, Reaction $reaction, DamageGraph $damageGraph, Precision $precision, OverheatingRate $overheatRate, Magazine $magazineData, int $initialAmmo, int $remainingAmmo, ReloadingData $reloadingData, Scope $scope) {
        $this->scheduler = $taskScheduler;

        $this->type = $type;
        $this->name = $name;

        $this->attackPoint = $attackPoint;
        $this->firingRate = $firingRate;
        $this->bulletSpeed = $bulletSpeed;
        $this->reaction = $reaction;
        $this->damageGraph = $damageGraph;
        $this->precision = $precision;
        $this->overheatRate = $overheatRate;

        $this->magazineData = $magazineData;
        $this->initialAmmo = $initialAmmo;
        $this->remainingAmmo = $remainingAmmo;

        $this->reloadingData = $reloadingData;

        $this->scope = $scope;


        $this->overheat = new Overheat($taskScheduler, $this->overheatRate,
            function (Player $player) {
                $player->sendTip("オーバーヒート");
                $this->cancelShooting();
                PlaySoundsService::playAround($player->getLevel(), $player->getPosition(), OtherGunSounds::LMGOverheat());
            },
            function (Player $player) {
                SendMessageService::sendBulletCount($player, $this->magazineData->getCurrentAmmo(), $this->remainingAmmo);
                PlaySoundsService::playAround($player->getLevel(), $player->getPosition(), OtherGunSounds::LMGReady());
            });

        if ($reloadingData instanceof MagazineReloadingData) {
            $this->reloading = new MagazineReloading($this->magazineData, $this->reloadingData);
        } else if ($reloadingData instanceof ClipReloadingData) {
            $this->reloading = new ClipReloading($this->magazineData, $reloadingData);
        } else if ($reloadingData instanceof OneByOneReloadingData) {
            $this->reloading = new OneByOneReloading($this->magazineData, $reloadingData);
        }

        $this->shooting = new Shooting(
            $this->scheduler,
            $this->type,
            $this->magazineData,
            $this->firingRate,
            $this->precision,
            $this->bulletSpeed,
            $this->reaction,
            function (Player $player) : void {
                $this->overheat->raise($this->overheatRate, $player);
                SendMessageService::sendBulletCount($player, $this->magazineData->getCurrentAmmo(), $this->remainingAmmo);
            }
        );
    }

    public function onCoolTime(): bool {
        return $this->shooting->isOnCoolTime();
    }

    public function isOverheated(): bool {
        return $this->overheat->isOverheated();
    }

    public function isReloading(): bool {
        return $this->reloading->isReloading();
    }

    public function cancelReloading(): void {
        $this->reloading->cancel();
    }

    public function reload(Player $player, int $inventoryAmmoAmount): void {
        $reduceBulletFunc = function ($value) use ($player): int {
            $this->remainingAmmo -= $value;
            SendMessageService::sendBulletCount($player, $this->getMagazineData()->getCurrentAmmo(), $this->remainingAmmo);
            return $this->remainingAmmo;
        };

        $this->reloading->execute($player, $this->scheduler, $inventoryAmmoAmount, $reduceBulletFunc);
    }

    public function cancelShooting(): void {
        $this->shooting->cancelShooting();
    }

    public function delayShoot(Player $player, float $second) {
        $this->shooting->delayShoot($player, $second);
    }

    public function shootOnce(Player $player): void {
        $this->shooting->shootOnce($player);
    }

    public function shoot(Player $player): void {
        $this->shooting->shoot($player);
    }

    /**
     * @return GunType
     */
    public function getType(): GunType {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return AttackPoint
     */
    public function getAttackPoint(): AttackPoint {
        return $this->attackPoint;
    }

    /**
     * @return FiringRate
     */
    public function getFiringRate(): FiringRate {
        return $this->firingRate;
    }

    /**
     * @return BulletSpeed
     */
    public function getBulletSpeed(): BulletSpeed {
        return $this->bulletSpeed;
    }

    /**
     * @return Reaction
     */
    public function getReaction(): Reaction {
        return $this->reaction;
    }

    /**
     * @return DamageGraph
     */
    public function getDamageGraph(): DamageGraph {
        return $this->damageGraph;
    }

    /**
     * @return Precision
     */
    public function getPrecision(): Precision {
        return $this->precision;
    }

    /**
     * @return OverheatingRate
     */
    public function getOverheatRate(): OverheatingRate {
        return $this->overheatRate;
    }

    /**
     * @return Magazine
     */
    public function getMagazineData(): Magazine {
        return $this->magazineData;
    }

    /**
     * @return int
     */
    public function getInitialAmmo(): int {
        return $this->initialAmmo;
    }

    /**
     * @return int
     */
    public function getRemainingAmmo(): int {
        return $this->remainingAmmo;
    }

    /**
     * @param int $remainingAmmo
     */
    public function setRemainingAmmo(int $remainingAmmo): void {
        $this->remainingAmmo = $remainingAmmo;
    }

    /**
     * @return ReloadingData
     */
    public function getReloadingData(): ReloadingData {
        return $this->reloadingData;
    }

    /**
     * @return Scope
     */
    public function getScope(): Scope {
        return $this->scope;
    }

    /**
     * @param Scope $scope
     */
    public function setScope(Scope $scope): void {
        $this->scope = $scope;
    }
}