<?php

namespace gun_system\controller\sounds_controllers;

use gun_system\models\GunSound;

class BulletSoundsController extends SoundsController
{

    public static function bulletFly(): GunSound {
        return new GunSound("gun.bullet.fly");
    }

    public static function bulletHitBlock(): GunSound {
        return new GunSound("gun.bullet.hit.block");
    }

    public static function bulletHitPlayer(): GunSound {
        return new GunSound("game.player.hurt");
    }

}