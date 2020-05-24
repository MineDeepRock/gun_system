<?php


namespace gun_system\models\shotgun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\OneByOneReloadingType;

class Model10A extends Shotgun
{
    const NAME = "Model10A";

    public function __construct() {
        parent::__construct(
            12,
            new BulletDamage(12),
            new GunRate(1.3),
            new BulletSpeed(333),
            1, new OneByOneReloadingType(18,6, 0.5),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(88, 88));
    }
}