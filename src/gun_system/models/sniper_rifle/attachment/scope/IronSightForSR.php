<?php


namespace gun_system\models\sniper_rifle\attachment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForSR extends SniperRifleScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}