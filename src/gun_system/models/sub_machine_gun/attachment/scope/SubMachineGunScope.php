<?php


namespace gun_system\models\sub_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;
use gun_system\models\attachment\Scope;
use gun_system\models\GunType;

class SubMachineGunScope extends Scope
{
    public function __construct(string $name, Magnification $magnification) {
        parent::__construct($name, $magnification, GunType::SMG());
    }
}