<?php


namespace gun_system\models\assault_rifle;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\ClipReloadingType;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;

class CeiRigotti extends AssaultRifle
{
    const NAME = "CeiRigotti";

    public function __construct() {
        parent::__construct(
            new BulletDamage(38),
            new GunRate(5),
            new BulletSpeed(700),
            0, new ClipReloadingType(70,10, 5, 1.5, 0.5),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(95, 90));
    }
}