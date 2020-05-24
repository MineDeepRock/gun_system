<?php


namespace gun_system\models\attachment;

use gun_system\models\GunType;

abstract class Attachment
{
    private $name;
    private $type;
    private $supportGunType;

    public function __construct(string $name, AttachmentType $type, GunType $supportGunType) {
        $this->name = $name;
        $this->type = $type;
        $this->supportGunType = $supportGunType;
    }

    /**
     * @return GunType
     */
    public function getSupportGunType(): GunType {
        return $this->supportGunType;
    }
}