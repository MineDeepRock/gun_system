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
use gun_system\model\reloading\MagazineData;
use gun_system\model\reloading\ReloadingData;

class Gun
{
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
     * @var MagazineData
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

    public function __construct(GunType $type, string $name, AttackPoint $attackPoint, FiringRate $firingRate, BulletSpeed $bulletSpeed, Reaction $reaction, DamageGraph $damageGraph, Precision $precision, OverheatingRate $overheatRate, MagazineData $magazineData, int $initialAmmo, int $remainingAmmo, ReloadingData $reloadingType, Scope $scope) {
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

        $this->reloadingData = $reloadingType;

        $this->scope = $scope;

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
     * @return MagazineData
     */
    public function getMagazineData(): MagazineData {
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