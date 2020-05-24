<?php


namespace gun_system\models\assault_rifle\attachiment\scope;


use gun_system\models\attachment\Magnification;
use gun_system\models\GunType;

class TwoFoldScopeForAR extends AssaultRifleScope
{
    public function __construct() {
        parent::__construct("2xScope", new Magnification(2));
    }
}