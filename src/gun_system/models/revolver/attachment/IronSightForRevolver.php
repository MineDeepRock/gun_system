<?php


namespace gun_system\models\revolver\attachment;


use gun_system\models\attachment\Magnification;

class IronSightForRevolver extends RevolverScope
{
public function __construct() {
    parent::__construct("IronSight", new Magnification(1));
}
}