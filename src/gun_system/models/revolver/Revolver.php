<?php


namespace gun_system\models\revolver;


use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\Gun;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\GunType;
use gun_system\models\OverheatRate;
use gun_system\models\ReloadingType;

class Revolver extends Gun
{
    public function __construct(BulletDamage $bulletDamage, GunRate $rate, BulletSpeed $bulletSpeed, float $reaction, ReloadingType $reloadingType, array $effectiveRange, GunPrecision $precision) {
        parent::__construct(GunType::Revolver(), $bulletDamage, $rate, $bulletSpeed, $reaction, $reloadingType, $effectiveRange, $precision, new OverheatRate(0));
    }
}