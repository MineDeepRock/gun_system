<?php


namespace gun_system\adapter;


use gun_system\model\attachment\Scope;
use gun_system\model\Gun;
use gun_system\model\GunType;
use gun_system\model\performance\AttackPoint;
use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\FiringRate;
use gun_system\model\performance\OverheatingRate;
use gun_system\model\performance\Precision;
use gun_system\model\performance\Reaction;
use gun_system\model\reloading_data\ClipReloadingData;
use gun_system\model\reloading_data\MagazineReloadingData;
use gun_system\model\Magazine;
use gun_system\model\reloading_data\OneByOneReloadingData;
use gun_system\service\LoadDamageGraphService;
use pocketmine\scheduler\TaskScheduler;

class GunJsonAdapter
{

    static function decode(TaskScheduler $taskScheduler, array $json): Gun {

        $reloadingData = null;

        switch ($json["reloading_data"]["type"]) {
            case "magazine":
                $reloadingData = new MagazineReloadingData($json["reloading_data"]["second"]);
                break;
            case "clip":
                $reloadingData = new ClipReloadingData($json["reloading_data"]["clip_capacity"], $json["reloading_data"]["second_of_clip"], $json["reloading_data"]["second_of_one"]);
                break;
            case "one_by_one":
                $reloadingData = new OneByOneReloadingData($json["reloading_data"]["second"]);
                break;
        }

        $damageGraph = LoadDamageGraphService::execute($json["name"]);

        return new Gun(
            $taskScheduler,
            new GunType($json["type"]),
            $json["name"],
            new AttackPoint($json["attack_point"]),
            new FiringRate($json["firing_rate"]),
            new BulletSpeed($json["bullet_speed"]),
            new Reaction($json["reaction"]),
            $damageGraph,
            new Precision($json["precision"]["ads"], $json["precision"]["hip_shooting"]),
            new OverheatingRate($json["overheating_rate"]),
            new Magazine($json["magazine_data"]["capacity"], $json["magazine_data"]["capacity"]),
            $json["initial_ammo"],
            $json["initial_ammo"],
            $reloadingData,
            new Scope(1));
    }
}