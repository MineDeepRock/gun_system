<?php


namespace gun_system\models\shotgun;


use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\Gun;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\GunType;
use gun_system\models\OverheatRate;
use gun_system\models\ReloadingType;

abstract class Shotgun extends Gun
{
    private $pellets;

    public function __construct(int $pellets, BulletDamage $bulletDamage, GunRate $rate, BulletSpeed $bulletSpeed, float $reaction, ReloadingType $reloadingType, array $effectiveRange, GunPrecision $precision) {
        $this->pellets = $pellets;
        parent::__construct(GunType::Shotgun(), $bulletDamage, $rate, $bulletSpeed, $reaction, $reloadingType, $effectiveRange, $precision, new OverheatRate(0));
    }

    /**
     * @return int
     */
    public function getPellets(): int {
        return $this->pellets;
    }
}