<?php


namespace gun_system\model\performance;


class Precision
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