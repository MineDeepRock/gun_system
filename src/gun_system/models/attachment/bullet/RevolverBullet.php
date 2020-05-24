<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\GunType;

class RevolverBullet extends Bullet
{
    public function __construct() {
        parent::__construct("RevolverBullet", GunType::Revolver());
    }
}