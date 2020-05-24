<?php


use gun_system\controller\EffectiveRangeController;
use gun_system\listener\GunListener;
use gun_system\pmmp\commands\GunCommand;
use pocketmine\entity\Entity;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        $controller = new EffectiveRangeController();
        $controller->loadAll();

        Entity::registerEntity(\gun_system\pmmp\entities\BulletEntity::class, true, ['Egg', 'minecraft:egg']);
        $this->getServer()->getCommandMap()->register("gun", new GunCommand($this, $this->getScheduler(), $this->getServer()));
        $this->getServer()->getPluginManager()->registerEvents(new GunListener(), $this);
    }
}