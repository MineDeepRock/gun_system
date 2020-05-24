<?php


namespace gun_system\pmmp\items;


use gun_system\interpreter\RevolverInterpreter;

class ItemRevolver extends ItemGun
{
public function __construct(string $name, RevolverInterpreter $interpreter) { parent::__construct($name, $interpreter); }
}