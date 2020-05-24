<?php


namespace gun_system\models\assault_rifle;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;

class Ribeyrolles extends AssaultRifle
{
    const NAME = "Ribeyrolles";

    public function __construct() {
        parent::__construct(
            new BulletDamage(28),
            new GunRate(9),
            new BulletSpeed(520),
            0,
            new MagazineReloadingType(100,25, 2),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(95, 90));
    }
}