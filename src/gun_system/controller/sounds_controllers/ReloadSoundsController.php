<?php

namespace gun_system\controller\sounds_controllers;

use gun_system\models\GunSound;

class ReloadSoundsController extends SoundsController
{
    public static function MagazineOut(): GunSound {
        return new GunSound("gun.reload.magazine.out");
    }
    public static function MagazineIn(): GunSound {
        return new GunSound("gun.reload.magazine.in");
    }

    public static function ClipPush(): GunSound {
        return new GunSound("gun.reload.clip.push");
    }

    public static function ClipPing(): GunSound {
        return new GunSound("gun.reload.clip.ping");
    }

    public static function ReloadOne(): GunSound {
        return new GunSound("gun.reload.clip.one");
    }
}