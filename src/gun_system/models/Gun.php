<?php


namespace gun_system\models;


use gun_system\models\GunType;
use pocketmine\utils\TextFormat;

abstract class Gun
{
    private $type;

    const NAME = "";

    private $bulletDamage;
    private $rate;
    private $bulletSpeed;
    private $reaction;
    private $effectiveRange;
    private $precision;
    private $remainingAmmo;
    private $reloadingType;
    private $overheatRate;

    private $moneyCost;
    private $killCountCondition;

    public function __construct(GunType $type, BulletDamage $bulletDamage, GunRate $rate, BulletSpeed $bulletSpeed, float $reaction, ReloadingType $reloadingType, array $effectiveRange, GunPrecision $precision, OverheatRate $overheatRate) {
        $this->type = $type;

        $this->bulletDamage = $bulletDamage;
        $this->rate = $rate;
        $this->bulletSpeed = $bulletSpeed;
        $this->reaction = $reaction;
        $this->effectiveRange = $effectiveRange;
        $this->reloadingType = $reloadingType;
        $this->remainingAmmo = $this->reloadingType->initialAmmo;

        $this->precision = $precision;
        $this->effectiveRange = $effectiveRange;
        $this->overheatRate = $overheatRate;
    }

    /**
     * @return BulletSpeed
     */
    public function getBulletSpeed(): BulletSpeed {
        return $this->bulletSpeed;
    }

    /**
     * @return float
     */
    public function getReaction(): float {
        return $this->reaction;
    }

    /**
     * @return mixed
     */
    public function getPrecision(): GunPrecision {
        return $this->precision;
    }

    /**
     * @return GunType
     */
    public function getType(): GunType {
        return $this->type;
    }

    /**
     * @return BulletDamage
     */
    public function getBulletDamage(): BulletDamage {
        return $this->bulletDamage;
    }

    /**
     * @return array
     */
    public function getEffectiveRange(): array {
        return $this->effectiveRange;
    }

    /**
     * @return GunRate
     */
    public function getRate(): GunRate {
        return $this->rate;
    }

    /**
     * @param GunPrecision $precision
     */
    public function setPrecision(GunPrecision $precision): void {
        $this->precision = $precision;
    }

    /**
     * @return ReloadingType
     */
    public function getReloadingType(): ReloadingType {
        return $this->reloadingType;
    }

    /**
     * @return OverheatRate
     */
    public function getOverheatRate(): OverheatRate {
        return $this->overheatRate;
    }

    /**
     * @return int
     */
    public function getRemainingAmmo(): int {
        return $this->remainingAmmo;
    }

    /**
     * @param int $remainingAmmo
     */
    public function setRemainingAmmo(int $remainingAmmo): void {
        $this->remainingAmmo = $remainingAmmo;
    }

    public function getDescribe(): string {
        $describe = "";
        $reloadingType = $this->getReloadingType();

        $describe .= "\n" . $this->getType()->getTypeText();
        $describe .= "\n" . $this::NAME;
        $describe .= "\n火";
        if ($this instanceof Shotgun) {
            if ($this->getBulletDamage()->getValue() * $this->getPellets() <= 100) {
                $describe .= str_repeat(TextFormat::GREEN . "■", ceil($this->getBulletDamage()->getValue() * $this->getPellets() / 2.5));
                $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil(($this->getBulletDamage()->getValue() * $this->getPellets() / 2.5)));
            } else {
                $describe .= str_repeat(TextFormat::GREEN . "■", 40);
            }
        } else if ($this->getBulletDamage()->getValue() <= 100) {
            $describe .= str_repeat(TextFormat::GREEN . "■", ceil($this->getBulletDamage()->getValue() / 2.5));
            $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil($this->getBulletDamage()->getValue() / 2.5));
        } else {
            $describe .= str_repeat(TextFormat::GREEN . "■", 40);
        }

        $describe .="\n" . TextFormat::WHITE . "速" ;
        $describe .= str_repeat(TextFormat::GREEN . "■", ceil($this->getBulletSpeed()->getPerSecond() / 25));
        $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil(($this->getBulletSpeed()->getPerSecond() / 25)));

        $describe .="\n" . TextFormat::WHITE . "ﾚｰﾄ" ;
        $describe .= str_repeat(TextFormat::GREEN . "■", ceil($this->getRate()->getPerSecond())*2);
        $describe .= str_repeat(TextFormat::WHITE . "■", 40 - ceil($this->getRate()->getPerSecond())*2);

        $describe .= "\n" . TextFormat::WHITE . "装弾数:" . $reloadingType->magazineCapacity . "/" . $reloadingType->initialAmmo;
        $describe .= "\n" . TextFormat::WHITE . "リロード時間:" . $reloadingType->secondToString();
        $describe .= "\n" . TextFormat::WHITE . "反動:" . $this->getReaction();

        $describe .= "\n" . TextFormat::WHITE . "精度:\n";
        if ($this->getPrecision()->getADS() <= 60) {
            $describe .= TextFormat::WHITE . "覗" . str_repeat(TextFormat::GREEN . "■", 1);
            $describe .= str_repeat(TextFormat::WHITE . "■", 39);
        } else {
            $describe .= TextFormat::WHITE . "覗" . str_repeat(TextFormat::GREEN . "■", 40 - (100 - ceil($this->getPrecision()->getADS())));
            $describe .= str_repeat(TextFormat::WHITE . "■", 100 - ceil($this->getPrecision()->getADS()));
        }

        if ($this->getPrecision()->getHipShooting() <= 60) {
            $describe .= "\n" . TextFormat::WHITE . "腰" . str_repeat(TextFormat::GREEN . "■", 1);
            $describe .= str_repeat(TextFormat::WHITE . "■", 39);

        } else {
            $describe .= "\n" . TextFormat::WHITE . "腰" . str_repeat(TextFormat::GREEN . "■", 40 - (100 - ceil($this->getPrecision()->getHipShooting())));
            $describe .= str_repeat(TextFormat::WHITE . "■", 100 - ceil($this->getPrecision()->getHipShooting()));
        }

        return $describe;
    }
}

class BulletDamage
{
    private $value;

    public function __construct(int $value) {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int {
        return $this->value;
    }
}

class GunPrecision
{
    private $percentADS;
    private $percentHipShooting;

    public function __construct(float $percentADS, float $percentHipShooting) {
        $this->percentADS = $percentADS;
        $this->percentHipShooting = $percentHipShooting;
    }

    /**
     * @return float
     */
    public function getADS(): float {
        return $this->percentADS;
    }

    /**
     * @return float
     */
    public function getHipShooting(): float {
        return $this->percentHipShooting;
    }

}


class BulletSpeed
{
    private $perSecond;

    public function __construct(float $perSecond) {

        $this->perSecond = $perSecond;
    }

    /**
     * @return mixed
     */
    public function getPerSecond() {
        return $this->perSecond;
    }

}

class GunRate
{
    private $perSecond;

    public function __construct(float $perSecond) {
        $this->perSecond = $perSecond;
    }

    /**
     * @return float
     */
    public function getPerSecond(): float {
        return $this->perSecond;
    }
}

class OverheatRate
{
    private $perShoot;

    public function __construct(float $perShoot) {
        $this->perShoot = $perShoot;
    }

    /**
     * @return float
     */
    public function getPerShoot(): float {
        return $this->perShoot;
    }
}

abstract class ReloadingType
{
    public $initialAmmo;
    public $magazineCapacity;

    public function __construct(int $initialAmmo, int $magazineCapacity) {
        $this->initialAmmo = $initialAmmo;
        $this->magazineCapacity = $magazineCapacity;
    }

    abstract function secondToString(): string;
}

class MagazineReloadingType extends ReloadingType
{
    public $second;

    public function __construct(int $initialAmmo, int $magazineCapacity, float $second) {
        parent::__construct($initialAmmo, $magazineCapacity);
        $this->second = $second;
    }

    function toString(): string {
        return "装填数:" . $this->magazineCapacity . ", リロード時間:" . $this->second;
    }

    function secondToString(): string {
        return $this->second . "s";
    }
}

class ClipReloadingType extends ReloadingType
{
    public $clipCapacity;
    public $secondOfClip;
    public $secondOfOne;

    public function __construct(int $initialAmmo, int $magazineCapacity, int $clipCapacity, float $secondOfClip, float $secondOfOne) {
        parent::__construct($initialAmmo, $magazineCapacity);
        $this->clipCapacity = $clipCapacity;
        $this->secondOfClip = $secondOfClip;
        $this->secondOfOne = $secondOfOne;
    }

    function secondToString(): string {
        return $this->secondOfOne . "s(" . $this->secondOfClip . "s)";
    }
}

class OneByOneReloadingType extends ReloadingType
{
    public $second;

    public function __construct(int $initialAmmo, int $magazineCapacity, float $second) {
        parent::__construct($initialAmmo, $magazineCapacity);
        $this->second = $second;
    }

    function secondToString(): string {
        return $this->second . "s";
    }
}


