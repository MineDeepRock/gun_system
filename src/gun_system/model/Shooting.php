<?php


namespace gun_system\model;


use Closure;
use gun_system\model\performance\BulletSpeed;
use gun_system\model\performance\FiringRate;
use gun_system\model\performance\Precision;
use gun_system\model\performance\Reaction;
use gun_system\pmmp\service\PlaySoundsService;
use gun_system\pmmp\service\SendMessageService;
use gun_system\pmmp\service\ShootingService;
use gun_system\pmmp\sounds\OtherGunSounds;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

class Shooting
{

    private $gunType;
    private $magazineData;

    private $firingRate;
    private $precision;
    private $bulletSpeed;
    private $reaction;

    private $onCoolTime;
    private $isShooting;

    private $scheduler;
    private $shootingTaskHandler;
    private $delayShootingTaskHandler;

    private $onShoot;

    public function __construct(TaskScheduler $scheduler, GunType $gunType, Magazine $magazineData, FiringRate $firingRate, Precision $precision, BulletSpeed $speed, Reaction $reaction, Closure $onShoot) {
        $this->scheduler = $scheduler;

        $this->gunType = $gunType;
        $this->magazineData = $magazineData;

        $this->firingRate = $firingRate;
        $this->precision = $precision;
        $this->bulletSpeed = $speed;
        $this->reaction = $reaction;


        $this->onCoolTime = false;
        $this->isShooting = false;

        $this->onShoot = $onShoot;
    }

    private function setCoolTime(Player $player): void {
        $this->onCoolTime = true;
        $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($player): void {
            if ($player === null) return;
            if ($player->isOnline()) {
                if ($this->gunType->equals(GunType::SniperRifle())) {
                    PlaySoundsService::play($player, OtherGunSounds::SniperRifleCocking());
                } else if ($this->gunType->equals(GunType::Shotgun())) {
                    PlaySoundsService::play($player, OtherGunSounds::ShotgunPumpAction());
                }
            }
            $this->onCoolTime = false;
        }), 20 * 1 / $this->firingRate->getPerSecond());
    }

    public function cancelShooting(): void {
        $this->isShooting = false;
        if ($this->shootingTaskHandler !== null)
            $this->shootingTaskHandler->cancel();
        if ($this->delayShootingTaskHandler !== null)
            $this->delayShootingTaskHandler->cancel();
    }

    public function delayShoot(Player $player, float $second) {
        $this->delayShootingTaskHandler = $this->scheduler->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($player): void {
            $this->shoot($player);
        }), 20 * $second);
    }

    public function shootOnce(Player $player): void {
        $this->setCoolTime($player);
        if (!$this->isShooting) {
            $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() - 1);
            ($this->onShoot)($player);
            ShootingService::execute($this->scheduler, $player, $this->gunType, $this->precision, $this->bulletSpeed, $this->reaction);
        }
    }

    public function shoot(Player $player): void {
        $this->isShooting = true;
        if ($this->shootingTaskHandler !== null)
            $this->shootingTaskHandler->cancel();
        if ($this->delayShootingTaskHandler !== null)
            $this->delayShootingTaskHandler->cancel();

        $this->shootingTaskHandler = $this->scheduler->scheduleRepeatingTask(
            new ClosureTask(function (int $currentTick) use ($player) : void {
                //TODO:あんまりよくない
                $this->setCoolTime($player);
                $this->magazineData->setCurrentAmmo($this->magazineData->getCurrentAmmo() - 1);

                ($this->onShoot)($player);
                ShootingService::execute($this->scheduler, $player, $this->gunType, $this->precision, $this->bulletSpeed, $this->reaction);
                if ($this->magazineData->getCurrentAmmo() === 0)
                    $this->cancelShooting();
            }), 20 * (1 / $this->firingRate->getPerSecond()));
    }

    /**
     * @return bool
     */
    public function isOnCoolTime(): bool {
        return $this->onCoolTime;
    }
}