<?php


namespace gun_system\models\shotgun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\ClipReloadingType;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;

class Model1900 extends Shotgun
{
    const NAME = "Model1900";

    public function __construct() {
        parent::__construct(
            12, new BulletDamage(13),
            new GunRate(20),
            new BulletSpeed(500),
            2, new ClipReloadingType(30,2, 2, 2.4, 3.2),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(88, 88));
    }
}