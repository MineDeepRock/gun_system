<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\AssaultRifleInterpreter;

class ItemAssaultRifle extends ItemGun
{
    public function __construct(string $name, AssaultRifleInterpreter $interpreter) { parent::__construct($name, $interpreter); }
}