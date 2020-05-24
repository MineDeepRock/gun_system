<?php


namespace gun_system\interpreter;


use gun_system\models\light_machine_gun\attachment\scope\IronSightForLMG;
use gun_system\models\light_machine_gun\attachment\scope\LightMachineGunScope;
use gun_system\models\light_machine_gun\LightMachineGun;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class LightMachineGunInterpreter extends GunInterpreter
{
    private $scope;

    public function __construct(LightMachineGun $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForLMG());
        parent::__construct($gun, $owner, $scheduler);
    }

    /**
     * @param LightMachineGunScope $scope
     */
    public function setScope(LightMachineGunScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return LightMachineGunScope
     */
    public function getScope(): LightMachineGunScope {
        return $this->scope;
    }
}