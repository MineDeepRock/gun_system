<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\GunType;

class LightMachineGunBullet extends Bullet
{
    public function __construct() { parent::__construct("LightMachineGunBullet",GunType::LMG()); }
}