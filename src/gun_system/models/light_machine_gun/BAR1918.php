<?php


namespace gun_system\models\light_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class BAR1918 extends LightMachineGun
{
    const NAME = "BAR1918";

    public function __construct() {
        parent::__construct(
            new BulletDamage(26),
            new GunRate(10),
            new BulletSpeed(820),
            new MagazineReloadingType(100,20, 3),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(98, 75),
            new OverheatRate(0));
    }
}