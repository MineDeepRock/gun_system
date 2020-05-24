<?php


namespace gun_system\models\light_machine_gun;


use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\Gun;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\GunType;
use gun_system\models\OverheatRate;
use gun_system\models\ReloadingType;

class LightMachineGun extends Gun
{
    public function __construct(BulletDamage $bulletDamage, GunRate $rate, BulletSpeed $bulletSpeed, ReloadingType $reloadingType, array $effectiveRange, GunPrecision $precision, OverheatRate $overheatRate) {
        parent::__construct(GunType::LMG(), $bulletDamage, $rate, $bulletSpeed, 0.0, $reloadingType, $effectiveRange, $precision, $overheatRate);
    }
}