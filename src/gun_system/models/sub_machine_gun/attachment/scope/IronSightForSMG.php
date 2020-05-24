<?php


namespace gun_system\models\sub_machine_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForSMG extends SubMachineGunScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}