<?php

namespace gun_system;

use gun_system\listener\GunListener;
use gun_system\pmmp\command\GunGiveCommand;
use gun_system\pmmp\entity\BulletEntity;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public function onEnable() {
        new GunSystem($this->getScheduler());

        $this->getServer()->getCommandMap()->register("gungive",new GunGiveCommand());
        Entity::registerEntity(BulletEntity::class, true, ['Egg', 'minecraft:egg']);
        $this->getServer()->getPluginManager()->registerEvents(new GunListener($this->getScheduler()), $this);
    }
}