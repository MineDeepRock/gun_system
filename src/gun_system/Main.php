<?php

namespace gun_system;

use gun_system\listener\GunListener;
use gun_system\pmmp\command\GunGiveCommand;
use gun_system\pmmp\entity\BulletEntity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class Main extends PluginBase
{
    public function onEnable():void {
        new GunSystem($this->getScheduler());

        $this->getServer()->getCommandMap()->register("gungive",new GunGiveCommand());
        EntityFactory::getInstance()->register(
            BulletEntity::class,
            function(World $world, CompoundTag $nbt):BulletEntity{
                return new BulletEntity(EntityDataHelper::parseLocation($nbt, $world),null, $nbt);
            }
            , ['Egg', 'minecraft:egg']
        );
        $this->getServer()->getPluginManager()->registerEvents(new GunListener($this->getScheduler()), $this);
    }
}