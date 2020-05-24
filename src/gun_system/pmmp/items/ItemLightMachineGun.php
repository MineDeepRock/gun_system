<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\LightMachineGunInterpreter;

class ItemLightMachineGun extends ItemGun
{
    public function __construct(string $name, LightMachineGunInterpreter $gun) {
        parent::__construct($name, $gun);
    }
}