<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\GunType;

class SubMachineGunBullet extends Bullet
{
    public function __construct() { parent::__construct("SubMachineGunBullet",GunType::SMG()); }
}