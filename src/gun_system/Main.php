<?php

namespace gun_system;

use gun_system\controller\EffectiveRangeController;
use gun_system\controller\EventController;
use gun_system\listener\GunListener;
use gun_system\models\GunList;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        new EventController($this);

        $controller = new EffectiveRangeController();
        $controller->loadAll();
        new GunList();

        new GunSystem($this->getScheduler());
        $this->getLogger()->info("GunSystemを読み込みました");
        Entity::registerEntity(\gun_system\pmmp\entities\BulletEntity::class, true, ['Egg', 'minecraft:egg']);
        $this->getServer()->getPluginManager()->registerEvents(new GunListener(), $this);
    }
}