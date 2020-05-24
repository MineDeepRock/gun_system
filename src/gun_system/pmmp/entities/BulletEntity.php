<?php

namespace gun_system\pmmp\entities;

use gun_system\controller\sounds_controllers\BulletSoundsController;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\Level;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\math\RayTraceResult;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;

class BulletEntity extends Projectile
{
    public const NETWORK_ID = self::EGG;

    public $width = 0.05;
    public $height = 0.05;
    protected $gravity = 0;

    public function __construct(Level $level, CompoundTag $nbt, ?Entity $shootingEntity = null) {
        parent::__construct($level, $nbt, $shootingEntity);
    }

    protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void {
        $blockHit->getLevel()->addParticle(new ExplodeParticle($blockHit));

        $players = Server::getInstance()->getOnlinePlayers();

        foreach ($players as $player) {
            if ($player !== null || $this->getOwningEntity() !== null) {
                $distance = $blockHit->distance($player->getPosition());
                if ($distance <= 2) {
                    BulletSoundsController::bulletHitBlock()->play($player);
                    //GameSystemBinder::getInstance()->getGameListener()->scare($player, $this->getOwningEntity());
                } else if ($distance <= 10) {
                    BulletSoundsController::bulletFly()->play($player);
                }
            }
        }

        parent::onHitBlock($blockHit, $hitResult);
    }

    protected function onHit(ProjectileHitEvent $event): void {
        if ($this->isAlive()) $this->kill();
    }
}