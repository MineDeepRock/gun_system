<?php


namespace gun_system;


use gun_system\interpreter\AssaultRifleInterpreter;
use gun_system\interpreter\HandGunInterpreter;
use gun_system\interpreter\LightMachineGunInterpreter;
use gun_system\interpreter\RevolverInterpreter;
use gun_system\interpreter\ShotgunInterpreter;
use gun_system\interpreter\SniperRifleInterpreter;
use gun_system\interpreter\SubMachineGunInterpreter;
use gun_system\models\assault_rifle\AssaultRifle;
use gun_system\models\assault_rifle\attachiment\scope\FourFoldScopeForAR;
use gun_system\models\assault_rifle\attachiment\scope\IronSightForAR;
use gun_system\models\assault_rifle\attachiment\scope\TwoFoldScopeForAR;
use gun_system\models\GunList;
use gun_system\models\hand_gun\attachment\scope\FourFoldScopeForHG;
use gun_system\models\hand_gun\attachment\scope\IronSightForHG;
use gun_system\models\hand_gun\attachment\scope\TwoFoldScopeForHG;
use gun_system\models\hand_gun\HandGun;
use gun_system\models\light_machine_gun\attachment\scope\FourFoldScopeForLMG;
use gun_system\models\light_machine_gun\attachment\scope\IronSightForLMG;
use gun_system\models\light_machine_gun\attachment\scope\TwoFoldScopeForLMG;
use gun_system\models\light_machine_gun\LightMachineGun;
use gun_system\models\revolver\Revolver;
use gun_system\models\shotgun\attachment\scope\IronSightForSG;
use gun_system\models\shotgun\Shotgun;
use gun_system\models\sniper_rifle\attachment\scope\FourFoldScopeForSR;
use gun_system\models\sniper_rifle\attachment\scope\IronSightForSR;
use gun_system\models\sniper_rifle\attachment\scope\TwoFoldScopeForSR;
use gun_system\models\sniper_rifle\SniperRifle;
use gun_system\models\sub_machine_gun\attachment\scope\FourFoldScopeForSMG;
use gun_system\models\sub_machine_gun\attachment\scope\IronSightForSMG;
use gun_system\models\sub_machine_gun\attachment\scope\TwoFoldScopeForSMG;
use gun_system\models\sub_machine_gun\SubMachineGun;
use gun_system\pmmp\items\ItemAssaultRifle;
use gun_system\pmmp\items\ItemGun;
use gun_system\pmmp\items\ItemHandGun;
use gun_system\pmmp\items\ItemLightMachineGun;
use gun_system\pmmp\items\ItemRevolver;
use gun_system\pmmp\items\ItemShotGun;
use gun_system\pmmp\items\ItemSniperRifle;
use gun_system\pmmp\items\ItemSubMachineGun;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\level\Level;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\TextFormat;

class GunSystem
{
    /**
     * @var TaskScheduler
     */
    static private $scheduler;

    public function __construct(TaskScheduler $scheduler) {
        self::$scheduler = $scheduler;
    }

    static function getGun(Player $owner, string $name, string $scopeName): ?ItemGun {
        $gun = GunList::fromString($name);
        if ($gun === null) return null;
        $itemGun = null;
        if ($gun instanceof AssaultRifle)
            $itemGun = new ItemAssaultRifle($gun::NAME, new AssaultRifleInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof Shotgun)
            $itemGun = new ItemShotgun($gun::NAME, new ShotgunInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof SubMachineGun)
            $itemGun = new ItemSubMachineGun($gun::NAME, new SubMachineGunInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof LightMachineGun)
            $itemGun = new ItemLightMachineGun($gun::NAME, new LightMachineGunInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof SniperRifle)
            $itemGun = new ItemSniperRifle($gun::NAME, new SniperRifleInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof HandGun)
            $itemGun = new ItemHandGun($gun::NAME, new HandGunInterpreter($gun, $owner, self::$scheduler));

        if ($gun instanceof Revolver)
            $itemGun = new ItemRevolver($gun::NAME, new RevolverInterpreter($gun, $owner, self::$scheduler));
        self::setScope($itemGun, $scopeName);
        $itemGun->setCustomName($gun::NAME);
        return self::setItemDescription($itemGun);
    }

    static private function setScope(ItemGun $gun, string $name) {
        if ($gun instanceof ItemAssaultRifle) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForAR());
                    break;
                case "2xScope":
                    $gun->getInterpreter()->setScope(new TwoFoldScopeForAR());
                    break;
                case "4xScope":
                    $gun->getInterpreter()->setScope(new FourFoldScopeForAR());
                    break;
            }
        } else if ($gun instanceof ItemHandGun) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForHG());
                    break;
                case "2xScope":
                    $gun->getInterpreter()->setScope(new TwoFoldScopeForHG());
                    break;
                case "4xScope":
                    $gun->getInterpreter()->setScope(new FourFoldScopeForHG());
                    break;
            }
        } else if ($gun instanceof ItemLightMachineGun) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForLMG());
                    break;
                case "2xScope":
                    $gun->getInterpreter()->setScope(new TwoFoldScopeForLMG());
                    break;
                case "4xScope":
                    $gun->getInterpreter()->setScope(new FourFoldScopeForLMG());
                    break;
            }
        } else if ($gun instanceof ItemShotGun) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForSG());
                    break;
            }
        } else if ($gun instanceof ItemSniperRifle) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForSR());
                    break;
                case "2xScope":
                    $gun->getInterpreter()->setScope(new TwoFoldScopeForSR());
                    break;
                case "4xScope":
                    $gun->getInterpreter()->setScope(new FourFoldScopeForSR());
                    break;
            }
        } else if ($gun instanceof ItemSubMachineGun) {
            switch ($name) {
                case "IronSight":
                    $gun->getInterpreter()->setScope(new IronSightForSMG());
                    break;
                case "2xScope":
                    $gun->getInterpreter()->setScope(new TwoFoldScopeForSMG());
                    break;
                case "4xScope":
                    $gun->getInterpreter()->setScope(new FourFoldScopeForSMG());
                    break;
            }
        }
    }

    static private function setItemDescription(ItemGun $item): ItemGun {
        $gun = $item->getGunData();
        return $item->setLore([$gun->getDescribe()]);
    }

    static function giveAmmo(Player $player, int $slot, int $value): bool {
        $gunItem = $player->getInventory()->getHotbarSlotItem($slot);
        if ($gunItem instanceof ItemGun) {
            $gun = $gunItem->getGunData();
            $empty = $gun->getReloadingType()->initialAmmo - $gun->getRemainingAmmo();
            if ($empty === 0) return false;
            if ($empty > $value) {
                $gunItem->getGunData()->setRemainingAmmo($gun->getRemainingAmmo() + $value);
                return true;
            } else {
                $gunItem->getGunData()->setRemainingAmmo($gun->getRemainingAmmo() + $empty);
                return true;
            }
        }

        return false;
    }

    static function threaten(Player $player) {
        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 5, 1));
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
}