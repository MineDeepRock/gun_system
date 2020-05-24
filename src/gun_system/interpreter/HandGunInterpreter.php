<?php


namespace gun_system\interpreter;


use gun_system\models\hand_gun\attachment\scope\HandGunScope;
use gun_system\models\hand_gun\attachment\scope\IronSightForHG;
use gun_system\models\hand_gun\HandGun;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class HandGunInterpreter extends GunInterpreter
{
    private $scope;

    public function __construct(HandGun $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForHG());
        parent::__construct($gun, $owner, $scheduler);
    }

    /**
     * @param HandGunScope $scope
     */
    public function setScope(HandGunScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return HandGunScope
     */
    public function getScope(): HandGunScope {
        return $this->scope;
    }
}