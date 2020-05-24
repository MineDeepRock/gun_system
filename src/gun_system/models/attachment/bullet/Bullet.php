<?php


namespace gun_system\models\attachment\bullet;


use gun_system\models\attachment\Attachment;
use gun_system\models\attachment\AttachmentType;
use gun_system\models\GunType;

abstract class Bullet extends Attachment
{
    public function __construct(string $name, GunType $supportGunType) {
        parent::__construct($name, AttachmentType::Bullet(), $supportGunType);
    }
}