<?php


namespace gun_system\models\attachment;


use gun_system\models\GunType;

abstract class Scope extends Attachment
{
    private $magnification;

    public function __construct(string $name, Magnification $magnification, GunType $supportGunType) {
        $this->magnification = $magnification;
        parent::__construct($name, AttachmentType::Scope(), $supportGunType);
    }

    /**
     * @return Magnification
     */
    public function getMagnification(): Magnification {
        return $this->magnification;
    }
}

class Magnification
{
    private $value;

    public function __construct(int $value) {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int {
        return $this->value;
    }
}