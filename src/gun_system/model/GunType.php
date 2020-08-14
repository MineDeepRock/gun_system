<?php


namespace gun_system\model;


class GunType
{
    /**
     * @var string
     */
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function equals(GunType $gunType): bool {
        return $this->type == $gunType->type;
    }

    public static function HandGun(): GunType {
        return new GunType("HandGun");
    }

    public static function AssaultRifle(): GunType {
        return new GunType("AssaultRifle");
    }

    public static function LMG(): GunType {
        return new GunType("LMG");
    }

    public static function Shotgun(): GunType {
        return new GunType("Shotgun");
    }

    public static function SniperRifle(): GunType {
        return new GunType("SniperRifle");
    }

    public static function SMG(): GunType {
        return new GunType("SMG");
    }

    public static function Revolver(): GunType {
        return new GunType("Revolver");
    }

    public static function DMR():GunType {
        return new GunType("DMR");
    }

    static function fromString(string $text): ?GunType {
        switch ($text) {
            case self::HandGun()->getTypeText():
                return self::HandGun();
                break;
            case self::AssaultRifle()->getTypeText():
                return self::AssaultRifle();
                break;
            case self::LMG()->getTypeText():
                return self::LMG();
                break;
            case self::Shotgun()->getTypeText():
                return self::Shotgun();
                break;
            case self::SniperRifle()->getTypeText():
                return self::SniperRifle();
                break;
            case self::SMG()->getTypeText():
                return self::SMG();
                break;
            case self::Revolver()->getTypeText():
                return self::Revolver();
                break;
            case self::DMR()->getTypeText():
                return self::DMR();
                break;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getTypeText() {
        return $this->type;
    }
}