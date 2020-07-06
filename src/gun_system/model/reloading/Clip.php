<?php


namespace gun_system\model\reloading;


class Clip extends ReloadingData
{
    private $clipCapacity;
    private $secondOfClip;
    private $secondOfOne;

    public function __construct(int $clipCapacity, float $secondOfClip, float $secondOfOne) {
        $this->clipCapacity = $clipCapacity;
        $this->secondOfClip = $secondOfClip;
        $this->secondOfOne = $secondOfOne;
    }

    /**
     * @return int
     */
    public function getClipCapacity(): int {
        return $this->clipCapacity;
    }

    /**
     * @return float
     */
    public function getSecondOfClip(): float {
        return $this->secondOfClip;
    }

    /**
     * @return float
     */
    public function getSecondOfOne(): float {
        return $this->secondOfOne;
    }
}