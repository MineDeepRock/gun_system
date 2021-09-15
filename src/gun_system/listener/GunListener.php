<?php

namespace gun_system\listener;


use gun_system\model\GunType;
use gun_system\pmmp\item\ItemGun;
use gun_system\service\LoadGunDataService;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\TaskScheduler;

class GunListener implements Listener
{
    private $scheduler;

    public function __construct(TaskScheduler $scheduler) {
        $this->scheduler = $scheduler;
    }

    public function tryShootingOnce(Player $player, Item $item): void {
        if ($item instanceof ItemGun) {
            if (!$player->getInventory()->contains(VanillaItems::ARROW())) {
                $player->sendMessage("矢がないと銃を撃つことはできません");
            } else {
                $item->shootOnce($player);
            }
        }
    }

    public function tryShooting(Player $player, Item $item): void {
        if ($item instanceof ItemGun) {
            if (!$player->getInventory()->contains(VanillaItems::ARROW())) {
                $player->sendMessage("矢がないと銃を撃つことはできません");
            } else if ($item->getGun()->getType()->equals(GunType::SniperRifle())) {
                $item->aim();
            } else {
                $item->shoot($player);
            }
        }
    }

    //todo
    //GunSystem
    //空中を右クリック,Tapで一発だけ射撃
    //public function onTapAir(DataPacketReceiveEvent $event) {
    //    $packet = $event->getPacket();
    //    if ($packet instanceof LevelSoundEventPacket) {
    //        if ($packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {
    //            $player = $event->getOrigin()->getPlayer();
    //            $item = $event->getOrigin()->getPlayer()->getInventory()->getItemInHand();
    //            $this->tryShootingOnce($player, $item);
    //        }
    //    }
    //}

    //空中を右クリックwin10,tap長押しで射撃
    public function onClickAir(PlayerInteractEvent $event) {
        //if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $this->tryShooting($player, $item);
        //}
    }

    //エンティティをなぐるで一発だけ射撃
    public function onTapPlayer(EntityDamageEvent $event) {
        if ($event instanceof EntityDamageByEntityEvent) {
            $player = $event->getDamager();
            if ($player instanceof Player
                && $event->getCause() === EntityDamageEvent::MODIFIER_ARMOR) {
                $item = $player->getInventory()->getItemInHand();
                $this->tryShootingOnce($player, $item);
            }
        }
    }

    //銃を捨てるでリロード
    public function onThrowAwayGun(InventoryTransactionEvent $event): void {
        $actions = array_values($event->getTransaction()->getActions());

        foreach (array_values($actions) as $action) {
            $item = $action->getTargetItem();

            if (file_exists(LoadGunDataService::PATH . $item->getName() . ".json")) {
                $event->cancel();
            }
        }
    }

    //アイテム持ち替えでリロードキャンセル
    public function onChangeHoldItem(PlayerItemHeldEvent $event) {
        $player = $event->getPlayer();
        $currentItem = $player->getInventory()->getItemInHand();
        $nextItem = $event->getItem();
        if ($currentItem instanceof ItemGun) {
            if ($currentItem->getName() === $nextItem->getName()) {
                $currentItem->reload($player);
            } else {
                $currentItem->cancelReloading();
            }
        }
    }

    //プレイヤーから半径3ブロック未満の地面tapでリロード
    public function onTapNearBlock(PlayerInteractEvent $event) {
        if (in_array($event->getAction(), [PlayerInteractEvent::RIGHT_CLICK_BLOCK])) {
            $player = $event->getPlayer();
            $touchedBlockPos = $event->getBlock()->getPosition();
            if ($player->getPosition()->distance($touchedBlockPos) < 3) {
                $item = $event->getItem();
                if ($item instanceof ItemGun) {
                    $item->reload($player);
                }
            }
        }
    }

    public function onSneak(PlayerToggleSneakEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($player->isSneaking()) {
            $player->getArmorInventory()->removeItem(VanillaBlocks::CARVED_PUMPKIN()->asItem());
            $player->getEffects()->remove(VanillaEffects::SLOWNESS());
        } else if ($item instanceof ItemGun) {
            $effectLevel = $item->getGun()->getScope()->getMagnification();
            $player->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), null, $effectLevel, false));
            if ($item->getGun()->getType()->equals(GunType::SniperRifle())) {
                $player->getArmorInventory()->setHelmet(VanillaBlocks::CARVED_PUMPKIN()->asItem());
            }
        }
    }
}