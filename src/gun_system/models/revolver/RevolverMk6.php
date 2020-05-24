<?php


namespace gun_system\models\revolver;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;

class RevolverMk6 extends Revolver
{
    const NAME = "RevolverMk6";

    public function __construct() {
        parent::__construct(
            new BulletDamage(53),
            new GunRate(3.3),
            new BulletSpeed(230),
            0,
            new MagazineReloadingType(36,6, 2.85),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(97, 90));
    }
}