<?php


namespace gun_system\models\attachment\bullet;



use gun_system\models\GunType;

class AssaultRifleBullet extends Bullet
{
    public function __construct() { parent::__construct("AssaultRifleBullet",GunType::AssaultRifle()); }
}