<?php


namespace gun_system;


use gun_system\model\Gun;
use gun_system\pmmp\item\ItemGun;
use gun_system\service\GenerateGunDescriptionService;
use gun_system\service\GiveScareService;
use gun_system\service\LoadGunDataService;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;
use pocketmine\world\World;

class GunSystem
{
    static private $scheduler;

    public function __construct(TaskScheduler $scheduler) {
        self::$scheduler = $scheduler;
    }

    static function loadAllGuns(): array {
        return LoadGunDataService::getAll(self::$scheduler);
    }

    static function findGunByName(string $name): Gun {
        return LoadGunDataService::findByName(self::$scheduler, $name);
    }

    static function getItemGun(string $name): ItemGun {
        $gun = self::findGunByName($name);
        $itemGun = new ItemGun($gun, self::$scheduler);
        $itemGun->setLore([GenerateGunDescriptionService::get($gun)]);
        return $itemGun;
    }

    static function getItemGunFromGun(Gun $gun): ItemGun {
        $itemGun = new ItemGun($gun, self::$scheduler);
        $itemGun->setLore([GenerateGunDescriptionService::get($gun)]);
        return $itemGun;
    }

    static function giveAmmo(Player $player, int $slot, int $value): bool {
        $gunItem = $player->getInventory()->getHotbarSlotItem($slot);
        if ($gunItem instanceof ItemGun) {
            $gun = $gunItem->getGun();
            $empty = $gun->getInitialAmmo() - $gun->getRemainingAmmo();
            if ($empty === 0) return false;
            if ($empty > $value) {
                $gunItem->getGun()->setRemainingAmmo($gun->getRemainingAmmo() + $value);
                return true;
            } else {
                $gunItem->getGun()->setRemainingAmmo($gun->getRemainingAmmo() + $empty);
                return true;
            }
        }

        return false;
    }

    static function giveScare(Player $player, int $tick, int $level) {
        GiveScareService::execute($player, $tick, $level);
    }

    static function sendHitMessage(Player $attacker, bool $isFinisher) {
        if ($isFinisher) {
            $attacker->sendTitle(TextFormat::RED . "><", "", 0, 1, 0);
        } else {
            $attacker->sendTitle("><", "", 0, 1, 0);
        }
    }

    static function sendHitParticle(World $world, Position $position, float $value, bool $isFinisher) {
        if ($isFinisher) {
            $text = str_repeat(TextFormat::RED . "■", intval($value));
        } else if ($value <= 5) {
            $text = str_repeat(TextFormat::WHITE . "■", intval($value));
        } else if ($value <= 15) {
            $text = str_repeat(TextFormat::GREEN . "■", intval($value));
        } else {
            $text = str_repeat(TextFormat::YELLOW . "■", intval($value));
        }

        $position = $position->add(rand(-2, 2), rand(0, 3), rand(-2, 2));
        $particle = new FloatingTextParticle($position, $text, "");
        $world->addParticle($position, $particle);

        self::$scheduler->scheduleDelayedTask(new ClosureTask(function () use ($position, $world, $particle): void {
            $particle->setInvisible(true);
            $world->addParticle($position, $particle);
        }), 20 * 1.5);
    }

    static function getGunDescription(Gun $gun): string {
        return GenerateGunDescriptionService::get($gun);
    }
}