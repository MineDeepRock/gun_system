<?php


namespace gun_system\models\assault_rifle\attachiment\scope;


use gun_system\models\attachment\Magnification;

class FourFoldScopeForAR extends AssaultRifleScope
{
    public function __construct() {
        parent::__construct("4xScope", new Magnification(4));
    }
}