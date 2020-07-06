<?php

namespace gun_system\pmmp\sounds;

use gun_system\model\GunSound;

class OtherGunSounds
{
    public static function ShotgunPumpAction(): GunSound {
        return new GunSound("gun.shotgun.pumpaction");
    }

    public static function SniperRifleCocking(): GunSound {
        return new GunSound("gun.sniperrifle.cocking");
    }

    //TODO:実装
    public static function OutOfBullet(): GunSound {
        return new GunSound("gun.outofbullet");
    }

    public static function LMGReady(): GunSound {
        return new GunSound("gun.lmg.ready");
    }

    public static function LMGOverheat(): GunSound {
        return new GunSound("gun.lmg.overheat");
    }
}