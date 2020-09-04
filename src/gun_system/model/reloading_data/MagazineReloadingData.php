<?php


namespace gun_system\model\reloading_data;


class MagazineReloadingData extends ReloadingData {
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

    public function toString(): string {
        return "{$this->second}s";
    }
}