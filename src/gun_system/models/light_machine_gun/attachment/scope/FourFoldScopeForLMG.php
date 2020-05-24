<?php


namespace gun_system\models\light_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class FourFoldScopeForLMG extends LightMachineGunScope
{
    public function __construct() {
        parent::__construct("4xScope", new Magnification(4));
    }
}