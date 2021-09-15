<?php

namespace gun_system\pmmp\entity;

use gun_system\pmmp\event\BulletHitBlockEvent;
use gun_system\pmmp\event\BulletHitEvent;
use gun_system\pmmp\event\BulletHitNearEvent;
use gun_system\pmmp\sounds\BulletSounds;
use gun_system\service\CalculateDamageService;
use gun_system\pmmp\service\PlaySoundsService;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\math\RayTraceResult;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\particle\ExplodeParticle;

class BulletEntity extends Projectile
{
    public $width = 0.05;
    public $height = 0.05;
    protected $gravity = 0;

    protected function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void {
        $shooter = $this->getOwningEntity();
        if ($shooter instanceof Player) {
            $bulletHitBlockEvent = new BulletHitBlockEvent($shooter, $blockHit);
            $bulletHitBlockEvent->call();
        }

        $blockHit->getPosition()->getWorld()->addParticle($blockHit->getPosition(), new ExplodeParticle());

        $players = Server::getInstance()->getOnlinePlayers();

        foreach ($players as $player) {
            if ($player !== null || $this->getOwningEntity() !== null) {
                $distance = $blockHit->getPosition()->distance($player->getPosition());
                if ($distance <= 3) {
                    PlaySoundsService::play($player, BulletSounds::BulletHitBlock());
                    $attacker = $this->getOwningEntity();
                    if ($attacker instanceof Player) {
                        $event = new BulletHitNearEvent($attacker, $player);
                        $event->call();
                    }
                } else if ($distance <= 10) {
                    PlaySoundsService::play($player, BulletSounds::BulletFly());
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
            $victim->getWorld()->addParticle($victim->getPosition()->add(0, 1, 0), new BlockBreakParticle(VanillaBlocks::REDSTONE()));

            $damage = CalculateDamageService::execute($attacker, $victim->getPosition());
            $event = new BulletHitEvent($attacker, $victim, $damage);
            $event->call();
        }
    }


    protected function getInitialSizeInfo() : EntitySizeInfo{ return new EntitySizeInfo(0.25, 0.25); }
    public static function getNetworkTypeId() : string{ return EntityIds::EGG; }
}