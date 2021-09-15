<?php


namespace gun_system\service;


use gun_system\pmmp\item\ItemGun;
use pocketmine\math\Vector3;
use pocketmine\Player;

class CalculateDamageService
{
    static function execute(?Player $attacker, Vector3 $targetVector): float {
        if ($attacker !== null) {

            $attackerPos = $attacker->getPosition();

            $distance = $attackerPos->distance($targetVector);
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