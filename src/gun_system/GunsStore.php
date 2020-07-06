<?php


namespace gun_system;


use gun_system\model\Gun;
use gun_system\service\LoadGunDataService;

class GunsStore
{
    static function getAllGuns(): array {
        return LoadGunDataService::getAll();
    }

    static function findGunByName(string $name): Gun {
        return LoadGunDataService::findByName($name);
    }
}