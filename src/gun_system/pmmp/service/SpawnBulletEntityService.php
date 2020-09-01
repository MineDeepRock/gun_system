<?php


namespace gun_system\pmmp\service;


use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\Precision;
use gun_system\pmmp\entity\BulletEntity;
use pocketmine\entity\Effect;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class SpawnBulletEntityService
{

    static function execute(TaskScheduler $scheduler, Player $player, Precision $precision, BulletSpeed $bulletSpeed) {

        $aimPos = $player->getDirectionVector();
        $precisionAsFloat = $player->isSneaking() ? $precision->getADS() : $precision->getHipShooting();
        if ($player->getEffect(Effect::NIGHT_VISION) !== null) {
            $precisionAsFloat -= (3 + $player->getEffect(Effect::NIGHT_VISION)->getAmplifier());
        }

        $nbt = new CompoundTag("", [
            "Pos" => new ListTag("Pos", [
                new DoubleTag("", $player->getX()),
                new DoubleTag("", $player->getY() + $player->getEyeHeight()),
                new DoubleTag("", $player->getZ())
            ]),
            "Motion" => new ListTag("Motion", [
                new DoubleTag("", $aimPos->getX() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200),
                new DoubleTag("", $aimPos->getY() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200),
                new DoubleTag("", $aimPos->getZ() + rand(-(100 - $precisionAsFloat), (100 - $precisionAsFloat)) / 200)
            ]),
            "Rotation" => new ListTag("Rotation", [
                new FloatTag("", $player->getYaw()),
                new FloatTag("", $player->getPitch())
            ]),
        ]);

        $projectile = new BulletEntity($player->getLevel(), $nbt, $player);
        $projectile->setMotion($projectile->getMotion()->multiply($bulletSpeed->getPerSecondBlock() / 27.8));

        $handle = $scheduler->scheduleDelayedRepeatingTask(new ClosureTask(
            function (int $currentTick) use ($projectile) : void {
                if (!$projectile->isClosed()) {
                    $projectile->getLevel()->addParticle(new CriticalParticle(new Vector3(
                        $projectile->getX(),
                        $projectile->getY(),
                        $projectile->getZ()
                    ), 4));
                }
            }
        ), 3, 1);

        //卵の速さが毎秒２７ブロック
        $projectile->spawnToAll();
        $scheduler->scheduleDelayedTask(new ClosureTask(
            function (int $currentTick) use ($projectile, $handle) : void {
                $handle->cancel();
                if (!$projectile->isClosed())
                    $projectile->close();
            }
        ), 20 * 5);

    }
}