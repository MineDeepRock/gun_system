<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\SubMachineGunInterpreter;
use pocketmine\Player;

class ItemSubMachineGun extends ItemGun
{
    public function __construct(string $name, SubMachineGunInterpreter $interpreter) {
        parent::__construct($name, $interpreter);
    }
}