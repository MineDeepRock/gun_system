<?php


namespace gun_system\service;


use gun_system\adapter\GunJsonAdapter;
use gun_system\model\Gun;

class LoadGunDataService
{
    private const PATH = ".\plugin_data\TeamSystem\players\\";

    static function execute(string $name): Gun {
        $data = json_decode(file_get_contents(self::PATH . $name . ".json"), true);
        return GunJsonAdapter::decode($data);
    }
}