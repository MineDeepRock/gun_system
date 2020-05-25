<?php


namespace gun_system\models\light_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class MG15 extends LightMachineGun
{
    const NAME = "MG15";

    public function __construct() {
        parent::__construct(
            new BulletDamage(28),
            new GunRate(8.3),
            new BulletSpeed(870),
            new MagazineReloadingType(100,100, 4.5),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(97, 75),
            new OverheatRate(2.9));
    }
}