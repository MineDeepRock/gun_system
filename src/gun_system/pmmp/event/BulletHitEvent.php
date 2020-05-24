<?php

namespace gun_system\pmmp\event;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class BulletHitEvent extends PluginEvent
{

    protected $eventName = "BulletHitEvent";

    /**
     * @var Player
     */
    private $attacker;
    /**
     * @var Entity
     */
    private $victim;
    /**
     * @var float
     */
    private $damage;

    public static $handlerList = null;

    public function __construct(Plugin $plugin, Player $attacker, Entity $victim, float $damage) {
        $this->attacker = $attacker;
        $this->victim = $victim;
        $this->damage = $damage;
        parent::__construct($plugin);
    }

    /**
     * @return Player
     */
    public function getAttacker(): Player {
        return $this->attacker;
    }

    /**
     * @return Entity
     */
    public function getVictim(): Entity {
        return $this->victim;
    }

    /**
     * @return float
     */
    public function getDamage(): float {
        return $this->damage;
    }
}