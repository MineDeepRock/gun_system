<?php

namespace gun_system;

use gun_system\controller\EffectiveRangeController;
use gun_system\controller\EventController;
use gun_system\listener\GunListener;
use gun_system\pmmp\commands\GunCommand;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        new EventController($this);

        $controller = new EffectiveRangeController();
        $controller->loadAll();

        $this->getLogger()->info("GunSystemを読み込みました");
        Entity::registerEntity(\gun_system\pmmp\entities\BulletEntity::class, true, ['Egg', 'minecraft:egg']);
        $this->getServer()->getCommandMap()->register("gun", new GunCommand($this, $this->getScheduler(), $this->getServer()));
        $this->getServer()->getPluginManager()->registerEvents(new GunListener(), $this);
    }
}