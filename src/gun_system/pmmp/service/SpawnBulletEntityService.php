<?php


namespace gun_system\pmmp\service;


use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\Precision;
use gun_system\pmmp\entity\BulletEntity;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\world\particle\CriticalParticle;

class SpawnBulletEntityService
{

    static function execute(TaskScheduler $scheduler, Player $player, Precision $precision, BulletSpeed $bulletSpeed) {

        $aimPos = $player->getDirectionVector();
        $precisionAsFloat = $player->isSneaking() ? $precision->getADS() : $precision->getHipShooting();
        if ($player->getEffects()->has(VanillaEffects::NIGHT_VISION())) {
            $precisionAsFloat -= (3 + $player->getEffects()->get(VanillaEffects::NIGHT_VISION())->getAmplifier());
        }

        $location = $player->getLocation();
        $location->z += $player->getEyeHeight();
        $nbt = CompoundTag::create();
        $nbt->setTag("Motion", new ListTag([
            new DoubleTag($aimPos->getX() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200),
            new DoubleTag($aimPos->getY() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200),
            new DoubleTag($aimPos->getZ() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200)
        ]));

        $projectile = new BulletEntity($location, $player, $nbt);
        $projectile->setMotion($projectile->getMotion()->multiply($bulletSpeed->getPerSecondBlock() / 27.8));

        $handle = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(
            function () use ($projectile): void {
                if (!$projectile->isClosed()) {
                    $projectile->getWorld()->addParticle($projectile->getPosition(), new CriticalParticle());
                }
            }
        ), 3, 1);

        //卵の速さが毎秒２７ブロック
        $projectile->spawnToAll();
        $scheduler->scheduleDelayedTask(new ClosureTask(
            function () use ($projectile, $handle): void {
                $handle->cancel();
                if (!$projectile->isClosed())
                    $projectile->close();
            }
        ), 20 * 5);

    }
}