<?php


namespace gun_system\models\sniper_rifle;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\ClipReloadingType;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;

class VetterliVitali extends SniperRifle
{
    const NAME = "VetterliVitali";

    public function __construct() {
        parent::__construct(
            new BulletDamage(100),
            new GunRate(0.76),
            new BulletSpeed(440),
            2.5, new ClipReloadingType(24,4, 4, 1.6, 0.76),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(99.5, 80));
    }
}