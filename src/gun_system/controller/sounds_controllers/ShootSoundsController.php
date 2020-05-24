<?php

namespace gun_system\controller\sounds_controllers;

use gun_system\models\GunSound;
use gun_system\models\GunType;

class ShootSoundsController extends SoundsController
{
    public static function shootSoundFromGunType(GunType $gunType): GunSound {
        switch ($gunType->getTypeText()) {
            case "HandGun":
                return self::HandGunShoot();
            case "AssaultRifle":
                return self::AssaultRifleShoot();
            case "LMG":
                return self::LMGShoot();
            case "Shotgun":
                return self::ShotgunShoot();
            case "SniperRifle":
                return self::SniperRifleShoot();
            case "SMG":
                return self::SMGShoot();
            case "Revolver":
                return self::RevolverShoot();
        }
        return new GunSound("");
    }

    public static function HandGunShoot(): GunSound {
        return new GunSound("gun.handgun.shoot");
    }

    public static function AssaultRifleShoot(): GunSound {
        return new GunSound("gun.assaultrifle.shoot");
    }

    public static function LMGShoot(): GunSound {
        return new GunSound("gun.lmg.shoot");
    }

    public static function ShotgunShoot(): GunSound {
        return new GunSound("gun.shotgun.shoot");
    }

    public static function SniperRifleShoot(): GunSound {
        return new GunSound("gun.sniperrifle.shoot");
    }

    public static function SMGShoot(): GunSound {
        return new GunSound("gun.smg.shoot");
    }

    public static function RevolverShoot(): GunSound {
        return new GunSound("gun.assaultrifle.shoot");
    }
}