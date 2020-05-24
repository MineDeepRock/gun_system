<?php


namespace gun_system\interpreter;


use gun_system\models\sub_machine_gun\attachment\scope\IronSightForSMG;
use gun_system\models\sub_machine_gun\attachment\scope\SubMachineGunScope;
use gun_system\models\sub_machine_gun\SubMachineGun;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class SubMachineGunInterpreter extends GunInterpreter
{
    private $scope;

    public function __construct(SubMachineGun $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForSMG());
        parent::__construct($gun, $owner, $scheduler);
    }

    /**
     * @param SubMachineGunScope $scope
     */
    public function setScope(SubMachineGunScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return SubMachineGunScope
     */
    public function getScope(): SubMachineGunScope {
        return $this->scope;
    }
}