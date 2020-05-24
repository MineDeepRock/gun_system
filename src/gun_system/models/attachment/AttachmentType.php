<?php


namespace gun_system\models\attachment;


class AttachmentType
{
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function equal(AttachmentType $gunType) :bool {
        return $this->type == $gunType->type;
    }

    public static function Scope():AttachmentType {
        return new AttachmentType("Scope");
    }

    public static function Bullet():AttachmentType {
        return new AttachmentType("Bullet");
    }

    public static function Magazine():AttachmentType {
        return new AttachmentType("Magazine");
    }

    public static function Muzzle():AttachmentType {
        return new AttachmentType("Muzzle");
    }
}