<?php

namespace gun_system;

use gun_system\controller\EffectiveRangeController;
use gun_system\listener\GunListener;
use gun_system\models\GunList;
use gun_system\pmmp\entities\BulletEntity;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        $controller = new EffectiveRangeController();
        $controller->loadAll();
        new GunList();

        new GunSystem($this->getScheduler());
        $this->getLogger()->info("GunSystemを読み込みました");
        Entity::registerEntity(BulletEntity::class, true, ['Egg', 'minecraft:egg']);
        $this->getServer()->getPluginManager()->registerEvents(new GunListener(), $this);
    }
}