<?php


namespace gun_system\service;


use gun_system\pmmp\item\ItemGun;
use pocketmine\block\Redstone;
use pocketmine\entity\Entity;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;

class CalculateDamageService
{
    static function execute(?Player $attacker, Entity $entity): float {
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
                $gun = $itemGun->getGun();

                if (intval($distance) > 99) {
                    $damage = $gun->getAttackPoint()->getValue() * $gun->getDamageGraph()->getGraph()[99] / 100;
                } else {
                    $damage = $gun->getAttackPoint()->getValue() * $gun->getDamageGraph()->getGraph()[intval($distance)] / 100;
                }

                return $damage / 5;
            }
        }
        return 0;
    }
}