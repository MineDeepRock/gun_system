<?php

namespace gun_system\pmmp\sounds;

use gun_system\model\GunSound;
use gun_system\model\GunType;

class ShootingSounds
{
    public static function ShootingSound(GunType $gunType): GunSound {
        switch ($gunType->getTypeText()) {
            case "HandGun":
                return self::HandGun();
            case "AssaultRifle":
                return self::AssaultRifle();
            case "LMG":
                return self::LMG();
            case "Shotgun":
                return self::Shotgun();
            case "SniperRifle":
                return self::SniperRifle();
            case "SMG":
                return self::SMG();
            case "Revolver":
                return self::Revolver();
            case "DMR":
                return self::DMR();
        }
        return new GunSound("");
    }

    public static function HandGun(): GunSound {
        return new GunSound("gun.handgun.shoot");
    }

    public static function AssaultRifle(): GunSound {
        return new GunSound("gun.assaultrifle.shoot");
    }

    public static function LMG(): GunSound {
        return new GunSound("gun.lmg.shoot");
    }

    public static function Shotgun(): GunSound {
        return new GunSound("gun.shotgun.shoot");
    }

    public static function SniperRifle(): GunSound {
        return new GunSound("gun.sniperrifle.shoot");
    }

    public static function SMG(): GunSound {
        return new GunSound("gun.smg.shoot");
    }

    public static function Revolver(): GunSound {
        return new GunSound("gun.assaultrifle.shoot");
    }

    public static function DMR(): GunSound {
        return new GunSound("gun.dmr.shoot");
    }
}