<?php


namespace gun_system\pmmp\service;


use gun_system\model\Gun;
use gun_system\model\GunType;
use pocketmine\utils\TextFormat;

class GenerateGunDescriptionService
{
    static function get(Gun $gun): string {
        $describe = "";

        $describe .= "\n" . $gun->getType()->getTypeText();
        $describe .= "\n" . $gun->getName();
        $describe .= "\n火";
        if ($gun->getType()->equals(GunType::Shotgun())) {
            if ($gun->getAttackPoint()->getValue() * 12 <= 100) {
                $describe .= str_repeat(TextFormat::GREEN . "■", ceil($gun->getAttackPoint()->getValue() * 12 / 2.5));
                $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil(($gun->getAttackPoint()->getValue() * 12 / 2.5)));
            } else {
                $describe .= str_repeat(TextFormat::GREEN . "■", 40);
            }
        } else if ($gun->getAttackPoint()->getValue() <= 100) {
            $describe .= str_repeat(TextFormat::GREEN . "■", ceil($gun->getAttackPoint()->getValue() / 2.5));
            $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil($gun->getAttackPoint()->getValue() / 2.5));
        } else {
            $describe .= str_repeat(TextFormat::GREEN . "■", 40);
        }

        $describe .="\n" . TextFormat::WHITE . "速" ;
        $describe .= str_repeat(TextFormat::GREEN . "■", ceil($gun->getBulletSpeed()->getPerSecondBlock() / 25));
        $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil(($gun->getBulletSpeed()->getPerSecondBlock() / 25)));

        $describe .="\n" . TextFormat::WHITE . "ﾚｰﾄ" ;
        $describe .= str_repeat(TextFormat::GREEN . "■", ceil($gun->getFiringRate()->getPerSecond())*2);
        $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil($gun->getFiringRate()->getPerSecond())*2);

        $describe .= "\n" . TextFormat::WHITE . "装弾数:" . $gun->getMagazineData()->getCapacity() . "/" . $gun->getInitialAmmo();
        $describe .= "\n" . TextFormat::WHITE . "リロード:" . $gun->getReloadingData()->toString();
        $describe .= "\n" . TextFormat::WHITE . "反動:" . $gun->getReaction()->getValue();

        $describe .= "\n" . TextFormat::WHITE . "精度:\n";
        if ($gun->getPrecision()->getADS() <= 60) {
            $describe .= TextFormat::WHITE . "覗" . str_repeat(TextFormat::GREEN . "■", 1);
            $describe .= str_repeat(TextFormat::WHITE . "■", 39);
        } else {
            $describe .= TextFormat::WHITE . "覗" . str_repeat(TextFormat::GREEN . "■", 40 - (100 - ceil($gun->getPrecision()->getADS())));
            $describe .= str_repeat(TextFormat::WHITE . "■", 100 - ceil($gun->getPrecision()->getADS()));
        }

        if ($gun->getPrecision()->getHipShooting() <= 60) {
            $describe .= "\n" . TextFormat::WHITE . "腰" . str_repeat(TextFormat::GREEN . "■", 1);
            $describe .= str_repeat(TextFormat::WHITE . "■", 39);

        } else {
            $describe .= "\n" . TextFormat::WHITE . "腰" . str_repeat(TextFormat::GREEN . "■", 40 - (100 - ceil($gun->getPrecision()->getHipShooting())));
            $describe .= str_repeat(TextFormat::WHITE . "■", 100 - ceil($gun->getPrecision()->getHipShooting()));
        }

        return $describe;
    }
}