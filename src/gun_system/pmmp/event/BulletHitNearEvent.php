<?php


namespace gun_system\pmmp\event;


use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class BulletHitNearEvent extends PluginEvent
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

    public function __construct(Plugin $plugin, Player $attacker, Player $victim) {
        $this->attacker = $attacker;
        $this->victim = $victim;
        parent::__construct($plugin);
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