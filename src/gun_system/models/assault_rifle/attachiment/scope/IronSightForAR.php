<?php


namespace gun_system\models\assault_rifle\attachiment\scope;


use gun_system\models\attachment\Magnification;

class IronSightForAR extends AssaultRifleScope
{
    public function __construct() {
        parent::__construct("IronSight", new Magnification(1));
    }
}