<?php


namespace gun_system\models\sub_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class FourFoldScopeForSMG extends SubMachineGunScope
{
    public function __construct() {
        parent::__construct("4xScope", new Magnification(4));
    }
}