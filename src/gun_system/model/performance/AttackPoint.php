<?php


namespace gun_system\model\performance;


class AttackPoint
{
    /**
     * @var float
     */
    private $value;

    public function __construct(float $value) {
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue(): float {
        return $this->value;
    }
}