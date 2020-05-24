<?php


namespace gun_system\interpreter;


use gun_system\models\assault_rifle\AssaultRifle;
use gun_system\models\assault_rifle\attachiment\scope\AssaultRifleScope;
use gun_system\models\assault_rifle\attachiment\scope\IronSightForAR;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class AssaultRifleInterpreter extends GunInterpreter
{
    private $scope;

    public function __construct(AssaultRifle $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForAR());
        parent::__construct($gun, $owner, $scheduler);
    }

    /**
     * @param AssaultRifleScope $scope
     */
    public function setScope(AssaultRifleScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return AssaultRifleScope
     */
    public function getScope(): AssaultRifleScope {
        return $this->scope;
    }
}