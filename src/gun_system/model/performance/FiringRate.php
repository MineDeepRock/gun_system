<?php


namespace gun_system\model\performance;


class FiringRate
{
    /**
     * @var float
     */
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