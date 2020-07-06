<?php


namespace gun_system\model;


class GunSound
{
    /**
     * @var string
     */
    private $name;
    private $volume;
    private $pitch;

    public function __construct(string $name, int $volume = 10, int $pitch = 2) {
        $this->name = $name;
        $this->volume = $volume;
        $this->pitch = $pitch;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getVolume(): int {
        return $this->volume;
    }

    /**
     * @return int
     */
    public function getPitch(): int {
        return $this->pitch;
    }
}