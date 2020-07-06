<?php

namespace gun_system\pmmp\entity;

use gun_system\pmmp\event\BulletHitEvent;
use gun_system\pmmp\event\BulletHitNearEvent;
use gun_system\pmmp\sounds\BulletSounds;
use gun_system\service\CalculateDamageService;
use gun_system\pmmp\service\PlaySoundsService;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\Level;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\math\RayTraceResult;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
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
                    PlaySoundsService::play($player,BulletSounds::BulletHitBlock());
                    $attacker = $this->getOwningEntity();
                    if($attacker instanceof Player){
                        $event = new BulletHitNearEvent($attacker, $player);
                        $event->call();
                    }
                } else if ($distance <= 10) {
                    PlaySoundsService::play($player,BulletSounds::BulletFly());
                }
            }
        }

        parent::onHitBlock($blockHit, $hitResult);
    }

    protected function onHit(ProjectileHitEvent $event): void {
        if ($this->isAlive()) $this->kill();
    }

    protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void {
        $victim = $entityHit;
        $attacker = $this->getOwningEntity();

        if ($attacker instanceof Player) {
            $damage = CalculateDamageService::execute($attacker, $victim);
            $event = new BulletHitEvent($attacker, $victim, $damage);
            $event->call();
        }
    }
}