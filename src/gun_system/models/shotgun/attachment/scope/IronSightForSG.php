<?php


namespace gun_system\models\shotgun\attachment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForSG extends ShotgunScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}