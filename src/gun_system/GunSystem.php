<?php


namespace gun_system;


use gun_system\model\Gun;
use gun_system\service\LoadGunDataService;

class GunSystem
{
    static function getGun(string $name): Gun {
        return LoadGunDataService::execute($name);
    }
}