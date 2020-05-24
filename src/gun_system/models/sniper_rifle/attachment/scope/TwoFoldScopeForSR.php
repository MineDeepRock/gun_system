<?php


namespace gun_system\models\sniper_rifle\attachment\scope;


use gun_system\models\attachment\Magnification;

class TwoFoldScopeForSR extends SniperRifleScope
{
    public function __construct() {
        parent::__construct("2xScope", new Magnification(2));
    }
}