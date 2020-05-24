<?php


namespace gun_system\models\hand_gun\attachment\scope;


use gun_system\models\attachment\Magnification;

class FourFoldScopeForHG extends HandGunScope
{
    public function __construct() {
        parent::__construct("4xScope", new Magnification(4));
    }
}