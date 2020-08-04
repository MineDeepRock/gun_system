<?php


namespace gun_system\model\reloading;


class OneByOne extends ReloadingData
{
    private $second;

    public function __construct(float $second) {
        $this->second = $second;
    }

    public function toString(): string {
        return "{$this->second}s";
    }

    /**
     * @return float
     */
    public function getSecond(): float {
        return $this->second;
    }
}