<?php


namespace gun_system\model\reloading;


class OneByOne extends ReloadingData
{
    public $second;

    public function __construct(float $second) {
        $this->second = $second;
    }

    public function toString(): string {
        return "{$this->second}s";
    }
}