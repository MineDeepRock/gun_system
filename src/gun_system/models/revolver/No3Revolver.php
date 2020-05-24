<?php


namespace gun_system\models\revolver;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;

class No3Revolver extends Revolver
{
    const NAME = "No3Revolver";

    public function __construct() {
        parent::__construct(
            new BulletDamage(53),
            new GunRate(2.7),
            new BulletSpeed(210),
            0,
            new MagazineReloadingType(30,6, 2.3),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(97, 90));
    }
}