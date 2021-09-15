<?php


namespace gun_system\pmmp\event;


use pocketmine\block\Block;
use pocketmine\event\Event;
use pocketmine\player\Player;

class BulletHitBlockEvent extends Event
{

    protected $eventName = "BulletHitNearEvent";

    /**
     * @var Player
     */
    private $shooter;
    /**
     * @var Block
     */
    private $block;

    public function __construct(Player $shooter, Block $block) {
        $this->shooter = $shooter;
        $this->block = $block;
    }

    /**
     * @return Player
     */
    public function getShooter(): Player {
        return $this->shooter;
    }

    /**
     * @return Block
     */
    public function getBlock(): Block {
        return $this->block;
    }
}