<?php


namespace gun_system\models\sub_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class Hellriegel1915 extends SubMachineGun
{
    const NAME = "Hellriegel1915";

    public function __construct() {
        parent::__construct(
            new BulletDamage(26),
            new GunRate(11),
            new BulletSpeed(380),
            new MagazineReloadingType(120,59, 3.8),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(90, 85),
            new OverheatRate(2.5));
    }
}