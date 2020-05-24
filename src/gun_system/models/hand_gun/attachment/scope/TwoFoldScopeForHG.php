<?php


namespace gun_system\models\hand_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class TwoFoldScopeForHG extends HandGunScope
{
    public function __construct() {
        parent::__construct("2xScope", new Magnification(2));
    }
}