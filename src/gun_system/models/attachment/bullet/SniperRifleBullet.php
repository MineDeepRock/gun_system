<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\GunType;

class SniperRifleBullet extends Bullet
{
    public function __construct() { parent::__construct("SniperRifleBullet",GunType::SniperRifle()); }
}