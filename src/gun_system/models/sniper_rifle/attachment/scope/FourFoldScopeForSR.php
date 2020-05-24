<?php


namespace gun_system\models\sniper_rifle\attachment\scope;


use gun_system\models\attachment\Magnification;

class FourFoldScopeForSR extends SniperRifleScope
{
    public function __construct() {
        parent::__construct("4xScope", new Magnification(4));
    }
}