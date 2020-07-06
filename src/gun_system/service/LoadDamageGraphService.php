<?php


namespace gun_system\service;



use gun_system\model\performance\DamageGraph;

class LoadDamageGraphService
{
    static function execute(string $name): DamageGraph {
        $im = imagecreatefrompng("./plugin_data/GunSystem/effective_ranges/" . $name . ".png");
        $range = [];
        $rgb = [];
        $x = 0;
        while ($x < 100) {
            $y = 0;
            while ($y < 100) {
                $imageColorAt = imagecolorat($im, $x, $y);
                $rgb['red'] = ($imageColorAt >> 16) & 0xFF;
                $rgb['green'] = ($imageColorAt >> 8) & 0xFF;
                $rgb['blue'] = $imageColorAt & 0xFF;
                if ($rgb['red'] === 255 && $rgb['green'] === 0 && $rgb['blue'] === 0) {
                    $range[$x] = 100 - $y;
                }
                $y++;
            }
            $x++;
        }
        imagedestroy($im);
        return new DamageGraph($range);
    }
}