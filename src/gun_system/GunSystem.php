<?php


namespace gun_system;


use gun_system\model\Gun;
use gun_system\pmmp\item\ItemGun;
use gun_system\pmmp\service\GenerateGunDescriptionService;
use gun_system\service\GiveScareService;
use gun_system\service\LoadGunDataService;
use pocketmine\level\Level;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\TextFormat;

class GunSystem
{
    static private $scheduler;

    public function __construct(TaskScheduler $scheduler) {
        self::$scheduler = $scheduler;
    }

    static function loadAllGuns(): array {
        return LoadGunDataService::getAll();
    }

    static function findGunByName(string $name): Gun {
        return LoadGunDataService::findByName($name);
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

    static function giveScare(Player $player) {
        GiveScareService::execute(self::$scheduler, $player);
    }

    static function sendHitMessage(Player $attacker, bool $isFinisher) {
        if ($isFinisher) {
            $attacker->addTitle(TextFormat::RED . "><", "", 0, 1, 0);
        } else {
            $attacker->addTitle("><", "", 0, 1, 0);
        }
    }

    static function sendHitParticle(Level $level, Position $position, float $value, bool $isFinisher) {
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
        $level->addParticle($particle);

        self::$scheduler->scheduleDelayedTask(new ClosureTask(function (int $tick) use ($level, $particle): void {
            $particle->setInvisible(true);
            $level->addParticle($particle);
        }), 20 * 1.5);
    }

    static function getGunDescription(Gun $gun): string {
        return GenerateGunDescriptionService::get($gun);
    }
}