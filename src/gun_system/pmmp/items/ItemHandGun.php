<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\HandGunInterpreter;

class ItemHandGun extends ItemGun
{
    public function __construct(string $name, HandGunInterpreter $interpreter) { parent::__construct($name, $interpreter); }
}