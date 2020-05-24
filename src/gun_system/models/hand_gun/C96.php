<?php


namespace gun_system\models\hand_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\OneByOneReloadingType;

class C96 extends HandGun
{
    const NAME = "C96";

    public function __construct() {
        parent::__construct(
            new BulletDamage(28),
            new GunRate(5),
            new BulletSpeed(440),
            0, new OneByOneReloadingType(40,10, 0.25),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(98, 95));
    }
}