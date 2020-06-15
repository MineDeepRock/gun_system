<?php

namespace gun_system\pmmp\event;

use pocketmine\entity\Entity;
use pocketmine\event\Event;
use pocketmine\Player;

class BulletHitEvent extends Event
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

    public function __construct(Player $attacker, Entity $victim, float $damage) {
        $this->attacker = $attacker;
        $this->victim = $victim;
        $this->damage = $damage;
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