<?php


namespace gun_system\model\performance;


class BulletSpeed
{
    /**
     * @var float
     */
    private $perSecondBlock;

    public function __construct(float $perSecondBlock) {
        $this->perSecondBlock = $perSecondBlock;
    }

    /**
     * @return float
     */
    public function getPerSecondBlock(): float {
        return $this->perSecondBlock;
    }
}