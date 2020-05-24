<?php


namespace gun_system\controller;


use gun_system\pmmp\items\ItemGun;
use pocketmine\block\Redstone;
use pocketmine\entity\Entity;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;

class DamageController
{

    static function calculateDamage(?Player $attacker, Entity $entity): float {
        if ($attacker !== null) {

            $attackerPos = new Vector3(
                $attacker->getX(),
                $attacker->getY(),
                $attacker->getZ()
            );
            $entityPo = new Vector3(
                $entity->getX(),
                $entity->getY(),
                $entity->getZ()
            );

            $distance = $attackerPos->distance($entityPo);

            $entity->getLevel()->addParticle(new DestroyBlockParticle(new Vector3(
                $entity->getX(),
                $entity->getY() + 1,
                $entity->getZ()
            ), new Redstone()));

            $itemGun = $attacker->getInventory()->getItemInHand();
            if ($itemGun instanceof ItemGun) {
                $gun = $itemGun->getGunData();

                if (intval($distance) > 99) {
                    $damage = $gun->getBulletDamage()->getValue() * $gun->getEffectiveRange()[99] / 100;
                } else {
                    $damage = $gun->getBulletDamage()->getValue() * $gun->getEffectiveRange()[intval($distance)] / 100;
                }

                return $damage / 5;
            }
        }
        return 0;
    }
}