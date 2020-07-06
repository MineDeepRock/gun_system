<?php


namespace gun_system\pmmp\sounds;


use gun_system\model\GunSound;

class BulletSounds
{
    public static function BulletFly(): GunSound {
        return new GunSound("gun.bullet.fly");
    }

    public static function BulletHitBlock(): GunSound {
        return new GunSound("gun.bullet.hit.block");
    }

    public static function BulletHitPlayer(): GunSound {
        return new GunSound("game.player.hurt");
    }
}