<?php


namespace gun_system\controller;


use gun_system\models\assault_rifle\CeiRigotti;
use gun_system\models\assault_rifle\FedorovAvtomat;
use gun_system\models\assault_rifle\M1907SL;
use gun_system\models\assault_rifle\Ribeyrolles;
use gun_system\models\hand_gun\C96;
use gun_system\models\hand_gun\HowdahPistol;
use gun_system\models\hand_gun\Mle1903;
use gun_system\models\hand_gun\P08;
use gun_system\models\light_machine_gun\BAR1918;
use gun_system\models\light_machine_gun\LewisGun;
use gun_system\models\light_machine_gun\MG15;
use gun_system\models\light_machine_gun\Chauchat;
use gun_system\models\revolver\ColtSAA;
use gun_system\models\revolver\NagantRevolver;
use gun_system\models\revolver\No3Revolver;
use gun_system\models\revolver\RevolverMk6;
use gun_system\models\shotgun\Automatic12G;
use gun_system\models\shotgun\M1897;
use gun_system\models\shotgun\Model10A;
use gun_system\models\shotgun\Model1900;
use gun_system\models\sniper_rifle\Gewehr98;
use gun_system\models\sniper_rifle\GewehrM95;
use gun_system\models\sniper_rifle\MartiniHenry;
use gun_system\models\sniper_rifle\SMLEMK3;
use gun_system\models\sniper_rifle\VetterliVitali;
use gun_system\models\sub_machine_gun\Automatico;
use gun_system\models\sub_machine_gun\FrommerStopAuto;
use gun_system\models\sub_machine_gun\Hellriegel1915;
use gun_system\models\sub_machine_gun\MP18;

class EffectiveRangeController
{
    private static $instance;

    public $ranges = [
            //Handgun
            Mle1903::NAME => [],
            P08::NAME => [],
            C96::NAME => [],
            HowdahPistol::NAME => [],
            //AssaultRifle
            M1907SL::NAME => [],
            CeiRigotti::NAME => [],
            FedorovAvtomat::NAME => [],
            Ribeyrolles::NAME => [],
            //Shotgun
            M1897::NAME => [],
            Model10A::NAME => [],
            Automatic12G::NAME => [],
            Model1900::NAME => [],
            //SniperRifle
            SMLEMK3::NAME => [],
            Gewehr98::NAME => [],
            MartiniHenry::NAME => [],
            VetterliVitali::NAME => [],
            GewehrM95::NAME => [],
            //SMG
            MP18::NAME => [],
            Automatico::NAME => [],
            Hellriegel1915::NAME => [],
            FrommerStopAuto::NAME => [],
            //LMG
            LewisGun::NAME => [],
            Chauchat::NAME => [],
            MG15::NAME => [],
            BAR1918::NAME => [],
            //LMG
            ColtSAA::NAME => [],
            RevolverMk6::NAME => [],
            No3Revolver::NAME => [],
            NagantRevolver::NAME => [],
    ];

    public function __construct() {
        self::$instance = $this;
    }

    public static function getInstance(): EffectiveRangeController {
        return self::$instance;
    }

    public function loadAll() {
        foreach ($this->ranges as $name => $range) {
            $path = "./plugin_data/GunSystem/effective_ranges/" . $name . ".png";
            if (file_exists($path)) {
                $this->ranges[$name] = $this->load($path);
            } else {
                $this->ranges[$name] = [];
            }
        }
    }

    public function load(string $path): array {
        $im = imagecreatefrompng($path);
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
        return $range;
    }
}