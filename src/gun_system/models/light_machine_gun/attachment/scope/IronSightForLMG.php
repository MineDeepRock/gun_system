<?php


namespace gun_system\models\light_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForLMG extends LightMachineGunScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}