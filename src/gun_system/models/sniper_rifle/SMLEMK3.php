<?php


namespace gun_system\models\sniper_rifle;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\ClipReloadingType;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;

class SMLEMK3 extends SniperRifle
{
    const NAME = "SMLEMK3";

    public function __construct() {
        parent::__construct(
            new BulletDamage(100),
            new GunRate(0.8),
            new BulletSpeed(740),
            2.5, new ClipReloadingType(20,10, 5, 1.8, 0.5),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(99.5, 80));
    }
}