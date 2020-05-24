<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\ShotgunInterpreter;

class ItemShotGun extends ItemGun
{
    public function __construct(string $name, ShotgunInterpreter $interpreter) {
        parent::__construct($name, $interpreter);
    }
}