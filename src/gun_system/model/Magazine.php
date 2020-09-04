<?php


namespace gun_system\model;


class Magazine
{
    private $currentAmmo;
    private $capacity;

    public function __construct(int $currentAmmo, int $capacity) {
        $this->currentAmmo = $currentAmmo;
        $this->capacity = $capacity;
    }

    /**
     * @return int
     */
    public function getCurrentAmmo(): int {
        return $this->currentAmmo;
    }

    /**
     * @return int
     */
    public function getCapacity(): int {
        return $this->capacity;
    }

    /**
     * @param int $currentAmmo
     */
    public function setCurrentAmmo(int $currentAmmo): void {
        $this->currentAmmo = $currentAmmo;
    }


    /**
     * @return bool
     */
    public function isEmpty(): bool {
        return $this->currentAmmo === 0;
    }

    /**
     * @return bool
     */
    public function isFull(): bool {
        return $this->currentAmmo === $this->capacity;
    }
}