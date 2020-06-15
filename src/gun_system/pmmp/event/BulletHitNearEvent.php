<?php


namespace gun_system\pmmp\event;


use pocketmine\event\Event;
use pocketmine\Player;

class BulletHitNearEvent extends Event
{

    protected $eventName = "BulletHitNearEvent";

    /**
     * @var Player
     */
    private $attacker;
    /**
     * @var Player
     */
    private $victim;

    public function __construct(Player $attacker, Player $victim) {
        $this->attacker = $attacker;
        $this->victim = $victim;
    }

    /**
     * @return Player
     */
    public function getAttacker(): Player {
        return $this->attacker;
    }

    /**
     * @return Player
     */
    public function getVictim(): Player {
        return $this->victim;
    }
}