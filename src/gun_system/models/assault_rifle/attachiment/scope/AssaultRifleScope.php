<?php


namespace gun_system\models\assault_rifle\attachiment\scope;


use gun_system\models\attachment\Magnification;
use gun_system\models\attachment\Scope;
use gun_system\models\GunType;

class AssaultRifleScope extends Scope
{
   public function __construct(string $name, Magnification $magnification) {
       parent::__construct($name, $magnification, GunType::AssaultRifle());
   }
}