<?php


namespace gun_system\model\performance;


class OverheatingRate
{
    /**
     * @var float
     */
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