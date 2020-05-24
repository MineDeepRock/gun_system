<?php


namespace gun_system\models\sub_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class TwoFoldScopeForSMG extends SubMachineGunScope
{
    public function __construct() {
        parent::__construct("2xScope", new Magnification(2));
    }
}