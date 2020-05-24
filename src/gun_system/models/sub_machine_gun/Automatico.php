<?php


namespace gun_system\models\sub_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class Automatico extends SubMachineGun
{
    const NAME = "Automatico";

    public function __construct() {
        parent::__construct(
            new BulletDamage(23),
            new GunRate(15),
            new BulletSpeed(380),
            new MagazineReloadingType(125,25, 2.1),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(90, 85),
            new OverheatRate(0));
    }
}