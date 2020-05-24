<?php


namespace gun_system\models\light_machine_gun;


use gun_system\controller\EffectiveRangeController;
use gun_system\models\BulletDamage;
use gun_system\models\BulletSpeed;
use gun_system\models\GunPrecision;
use gun_system\models\GunRate;
use gun_system\models\MagazineReloadingType;
use gun_system\models\OverheatRate;

class LewisGun extends LightMachineGun
{
    const NAME = "LewisGun";

    public function __construct() {
        parent::__construct(
            new BulletDamage(26),
            new GunRate(8),
            new BulletSpeed(740),
            new MagazineReloadingType(94,47, 2.85),
            EffectiveRangeController::getInstance()->ranges[self::NAME],
            new GunPrecision(98.5, 77),
            new OverheatRate(0));
    }
}