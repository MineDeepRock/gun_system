<?php


namespace gun_system\models\revolver;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\OneByOneReloadingType;

class NagantRevolver extends Revolver
{
    const NAME = "NagantRevolver";

    public function __construct() {
        parent::__construct(
            new BulletDamage(40),
            new GunRate(3.3),
            new BulletSpeed(335),
            0,
            new OneByOneReloadingType(21,7, 1.3),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(97, 90));
    }
}