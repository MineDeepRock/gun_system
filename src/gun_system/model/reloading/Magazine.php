<?php


namespace gun_system\model\reloading;


class Magazine extends ReloadingData {
    private $second;

    public function __construct(float $second) {
        $this->second = $second;
    }

    /**
     * @return float
     */
    public function getSecond(): float {
        return $this->second;
    }
}