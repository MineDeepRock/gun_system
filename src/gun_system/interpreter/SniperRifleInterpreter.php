<?php


namespace gun_system\interpreter;


use gun_system\controller\sounds_controllers\OtherGunSoundsController;
use gun_system\models\sniper_rifle\attachment\scope\IronSightForSR;
use gun_system\models\sniper_rifle\attachment\scope\SniperRifleScope;
use gun_system\models\sniper_rifle\SniperRifle;
use pocketmine\Player;
use pocketmine\scheduler\TaskScheduler;

class SniperRifleInterpreter extends GunInterpreter
{
    /**
     * @var SniperRifleScope
     */
    private $scope;

    public function __construct(SniperRifle $gun, Player $owner, TaskScheduler $scheduler) {
        $this->setScope(new IronSightForSR());

        parent::__construct($gun, $owner, $scheduler);
        $this->setWhenBecomeReady(function () {
            OtherGunSoundsController::SniperRifleCocking()->play($this->owner);
        });
    }

    public function aim(): void {
        //TODO:スニークさせたい
    }

    /**
     * @param SniperRifleScope $scope
     */
    public function setScope(SniperRifleScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return SniperRifleScope
     */
    public function getScope(): SniperRifleScope {
        return $this->scope;
    }
}