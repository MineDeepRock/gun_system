<?php


namespace gun_system\models;


use pocketmine\item\ItemIds;

class BulletId
{
    public const HAND_GUN = ItemIds::PAPER;
    public const ASSAULT_RIFLE = ItemIds::GLOWSTONE_DUST;
    public const LMG = ItemIds::FLINT;
    public const SNIPER_RIFLE = ItemIds::SUGAR;
    public const SMG = ItemIds::RABBIT_FOOT;
    public const REVOLVER = ItemIds::RABBIT_HIDE;

    public const SHOTGUN = ItemIds::BRICK;

    public static function fromGunType(GunType $gunType): int {
        switch ($gunType->getTypeText()) {
            case GunType::HandGun()->getTypeText():
                return self::HAND_GUN;
            case GunType::AssaultRifle()->getTypeText():
                return self::ASSAULT_RIFLE;
            case GunType::Shotgun()->getTypeText():
                return self::SHOTGUN;
            case GunType::LMG()->getTypeText():
                return self::LMG;
            case GunType::SniperRifle()->getTypeText():
                return self::SNIPER_RIFLE;
            case GunType::SMG()->getTypeText():
                return self::SMG;
            case GunType::Revolver()->getTypeText():
                return self::REVOLVER;
        }
        return self::HAND_GUN;
    }
}


