<?php


namespace gun_system\controller;


use gun_system\pmmp\event\BulletHitEvent;
use gun_system\pmmp\event\BulletHitNearEvent;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class EventController
{
    private static $instance;
    private $plugin;

    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        self::$instance = $this;
    }

    /**
     * @return EventController
     */
    public static function getInstance(): EventController {
        return self::$instance;
    }

    public function callBulletHitEvent(Player $attacker, Entity $victim, float $damage): void {
        $event = new BulletHitEvent($this->plugin, $attacker, $victim, $damage);
        $event->call();
    }

    public function callBulletHitNearEvent(Player $attacker, Player $victim): void {
        $event = new BulletHitNearEvent($this->plugin, $attacker, $victim);
        $event->call();
    }


}