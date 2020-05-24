<?php

namespace gun_system\controller\sounds_controllers;

use gun_system\models\GunSound;

class OtherGunSoundsController extends SoundsController
{
    public static function ShotgunPumpAction(): GunSound {
        return new GunSound("gun.shotgun.pumpaction");
    }

    public static function SniperRifleCocking(): GunSound {
        return new GunSound("gun.sniperrifle.cocking");
    }

    //TODO:実装
    public static function outOfBullet(): GunSound {
        return new GunSound("gun.outofbullet");
    }

    public static function LMGReady(): GunSound {
        return new GunSound("gun.lmg.ready");
    }

    public static function LMGOverheat(): GunSound {
        return new GunSound("gun.lmg.overheat");
    }
}