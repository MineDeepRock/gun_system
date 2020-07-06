<?php


namespace gun_system\model\attachment;


class Scope
{
    /**
     * @var int
     */
    private $magnification;

    public function __construct(int $magnification) {
        $this->magnification = $magnification;
    }

    /**
     * @return int
     */
    public function getMagnification(): int {
        return $this->magnification;
    }
}