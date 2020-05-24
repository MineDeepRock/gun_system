<?php

namespace gun_system\models;

class GunType
{
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function equal(GunType $gunType): bool {
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

    /**
     * @return mixed
     */
    public function getTypeText() {
        return $this->type;
    }
}