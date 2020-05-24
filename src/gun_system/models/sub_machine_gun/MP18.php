<?php


namespace gun_system\models\sub_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class MP18 extends SubMachineGun
{
    const NAME = "MP18";

    public function __construct() {
        parent::__construct(
            new BulletDamage(28),
            new GunRate(9),
            new BulletSpeed(420),
            new MagazineReloadingType(96,32, 2),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(98, 95),
            new OverheatRate(0));
    }
}