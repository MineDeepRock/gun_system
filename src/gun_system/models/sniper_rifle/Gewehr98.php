<?php


namespace gun_system\models\sniper_rifle;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\ClipReloadingType;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;

class Gewehr98 extends SniperRifle
{
    const NAME = "Gewehr98";

    public function __construct() {
        parent::__construct(
            new BulletDamage(100),
            new GunRate(0.8),
            new BulletSpeed(880),
            2.5, new ClipReloadingType(25,5, 5, 1.5, 0.5),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(99.5, 80));
    }
}