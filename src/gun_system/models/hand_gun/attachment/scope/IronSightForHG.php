<?php


namespace gun_system\models\hand_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForHG extends HandGunScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}