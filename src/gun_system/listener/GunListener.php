<?php

namespace gun_system\listener;


use gun_system\controller\DamageController;
use gun_system\controller\EventController;
use gun_system\pmmp\entities\BulletEntity;
use gun_system\pmmp\event\BulletHitEvent;
use gun_system\pmmp\items\ItemGun;
use gun_system\pmmp\items\ItemSniperRifle;
use pocketmine\block\Redstone;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\transaction\action\DropItemAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class GunListener implements Listener
{
    public function tryShootingOnce(Player $player, Item $item): void {
        if ($item instanceof ItemGun) {
            if (!$player->getInventory()->contains(ItemFactory::get(Item::ARROW, 0, 1))) {
                $player->sendMessage("矢がないと銃を撃つことはできません");
            } else {
                $item->shootOnce();
            }
        }
    }

    public function tryShooting(Player $player, Item $item): void {
        if ($item instanceof ItemGun) {
            if (!$player->getInventory()->contains(ItemFactory::get(Item::ARROW, 0, 1))) {
                $player->sendMessage("矢がないと銃を撃つことはできません");
            } else if ($item instanceof ItemSniperRifle) {
                $item->aim();
            } else {
                $item->shoot();
            }
        }
    }

    public function tryReloading(Item $item): void {
        if ($item instanceof ItemGun) {
            $item->reload();
        }
    }

    public function tryCancelReloading(Item $item): void {
        if ($item instanceof ItemGun) {
            $item->cancelReloading();
        }
    }

    //GunSystem
    //空中を右クリック,Tapで一発だけ射撃
    public function onTapAir(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        if ($packet instanceof LevelSoundEventPacket) {
            if ($packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {
                $player = $event->getPlayer();
                $item = $event->getPlayer()->getInventory()->getItemInHand();
                $this->tryShootingOnce($player, $item);
            }
        }
    }

    //空中を右クリックwin10,tap長押しで射撃
    public function onClickAir(PlayerInteractEvent $event) {
        if (in_array($event->getAction(), [PlayerInteractEvent::RIGHT_CLICK_AIR])) {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $this->tryShooting($player, $item);
        }
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
        $player = $event->getTransaction()->getSource();
        $actions = array_values($event->getTransaction()->getActions());

        $dropItemActions = array_values(array_filter($actions, function ($item) {
            return $item instanceof DropItemAction;
        }));
        $slotChangeActions = array_values(array_filter($actions, function ($item) {
            return $item instanceof SlotChangeAction;
        }));


        if (count($dropItemActions) !== 0 && count($slotChangeActions) !== 0) {
            $event->setCancelled();
        }
    }

    //アイテム持ち替えでリロードキャンセル
    public function onChangeHoldItem(\pocketmine\event\player\PlayerItemHeldEvent $event) {
        $player = $event->getPlayer();
        $currentItem = $player->getInventory()->getItemInHand();
        $nextItem = $event->getItem();
        if ($currentItem instanceof \gun_system\pmmp\items\ItemGun) {
            if ($currentItem->getName() === $nextItem->getName()) {
                $this->tryReloading($currentItem);
            } else {
                $this->tryCancelReloading($currentItem);
            }
        }
    }

    //プレイヤーから半径3ブロック未満の地面tapでリロード
    public function onTapNearBlock(PlayerInteractEvent $event) {
        if (in_array($event->getAction(), [PlayerInteractEvent::RIGHT_CLICK_BLOCK])) {
            $player = $event->getPlayer();
            $touchedBlockPos = new Vector3(
                $event->getBlock()->getX(),
                $event->getBlock()->getY(),
                $event->getBlock()->getZ()
            );
            if ($player->getPosition()->distance($touchedBlockPos) < 3) {
                $item = $event->getItem();
                $this->tryReloading($item);
            }
        }
    }

    public function onSneak(PlayerToggleSneakEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($player->isSneaking()) {
            $player->getArmorInventory()->removeItem(ItemFactory::get(Item::PUMPKIN));
            $player->removeEffect(Effect::SLOWNESS);
        } else {
            if (is_subclass_of($item, "gun_system\pmmp\items\ItemGun")) {
                $effectLevel = $item->getInterpreter()->getScope()->getMagnification()->getValue();
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), null, $effectLevel, false));
                if ($item instanceof ItemSniperRifle) {
                    $player->getArmorInventory()->setHelmet(ItemFactory::get(Item::PUMPKIN));
                }
            }
        }
    }

    public function onProjectileHit(ProjectileHitEntityEvent $event) {
        $bullet = $event->getEntity();
        $victim = $event->getEntityHit();
        $attacker = $bullet->getOwningEntity();

        if ($bullet instanceof \gun_system\pmmp\entities\BulletEntity && $attacker instanceof Player) {
            $damage = DamageController::calculateDamage($attacker, $victim);
            EventController::getInstance()->callBulletHitEvent($attacker, $victim, $damage);
        }
    }
}