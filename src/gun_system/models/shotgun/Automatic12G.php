<?php


namespace gun_system\models\shotgun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\OneByOneReloadingType;

class Automatic12G extends Shotgun
{
    const NAME = "Automatic12G";

    public function __construct() {
        parent::__construct(
            12,
            new BulletDamage(7.7),
            new GunRate(4.2),
            new BulletSpeed(333),
            1, new OneByOneReloadingType(35,5, 0.7),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(88, 88));
    }
}