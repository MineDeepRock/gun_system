<?php


namespace gun_system\interpreter;


use gun_system\models\revolver\attachment\IronSightForRevolver;
use gun_system\models\revolver\attachment\RevolverScope;
use gun_system\models\revolver\Revolver;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class RevolverInterpreter extends GunInterpreter
{
    private $scope;

    public function __construct(Revolver $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForRevolver());
        parent::__construct($gun, $owner, $scheduler);
    }

    /**
     * @param RevolverScope $scope
     */
    public function setScope(RevolverScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return RevolverScope
     */
    public function getScope(): RevolverScope {
        return $this->scope;
    }
}