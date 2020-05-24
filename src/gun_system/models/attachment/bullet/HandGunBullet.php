<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\GunType;

class HandGunBullet extends Bullet
{
    public function __construct() { parent::__construct("HandGunBullet",GunType::HandGun()); }

}