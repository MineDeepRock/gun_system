<?php


namespace gun_system\service;


use gun_system\adapter\GunJsonAdapter;
use gun_system\model\Gun;
use pocketmine\scheduler\TaskScheduler;

class LoadGunDataService
{
    const PATH = "./plugin_data/GunSystem/gun_spec/";

    static function findByName(TaskScheduler $taskScheduler, string $name): Gun {
        $data = json_decode(file_get_contents(self::PATH . $name . ".json"), true);
        return GunJsonAdapter::decode($taskScheduler, $data);
    }

    static function getAll(TaskScheduler $taskScheduler): array {
        $guns = [];
        $dh = opendir(self::PATH);
        while (($fileName = readdir($dh)) !== false) {
            if (filetype(self::PATH . $fileName) === "file") {
                $data = json_decode(file_get_contents(self::PATH . $fileName), true);
                $guns[] = GunJsonAdapter::decode($taskScheduler, $data);
            }
        }
        closedir($dh);

        return $guns;
    }
}