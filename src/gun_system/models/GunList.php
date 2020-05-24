<?php


namespace gun_system\models;


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

class GunList
{
    private $ar = [];
    private $hg = [];
    private $sg = [];
    private $sn = [];
    private $smg = [];
    private $lmg = [];
    private $rv = [];

    public function __construct() {
        $this->ar = [
            new M1907SL(),
            new CeiRigotti(),
            new FedorovAvtomat(),
            new Ribeyrolles(),
        ];
        $this->hg = [
            new Mle1903(),
            new P08(),
            new C96(),
            new HowdahPistol(),
        ];
        $this->sg = [
            new M1897(),
            new Model10A(),
            new Automatic12G(),
            new Model1900(),
        ];
        $this->sn = [
            new SMLEMK3(),
            new Gewehr98(),
            new MartiniHenry(),
            new VetterliVitali(),
            new GewehrM95(),
        ];
        $this->smg = [
            new MP18(),
            new Automatico(),
            new Hellriegel1915(),
            new FrommerStopAuto(),
        ];
        $this->lmg = [
            new LewisGun(),
            new Chauchat(),
            new MG15(),
            new BAR1918()
        ];
        $this->rv = [
            new ColtSAA(),
            new NagantRevolver(),
            new No3Revolver(),
            new RevolverMk6(),
        ];
    }

    static function fromString(string $string): ?Gun {
        switch ($string) {
            //Handgun
            case Mle1903::NAME:
                return new Mle1903();
                break;
            case P08::NAME:
                return new P08();
                break;
            case C96::NAME:
                return new C96();
                break;
            case HowdahPistol::NAME:
                return new HowdahPistol();
                break;

            //AssaultRifle
            case M1907SL::NAME:
                return new M1907SL();
                break;
            case CeiRigotti::NAME:
                return new CeiRigotti();
                break;
            case FedorovAvtomat::NAME:
                return new FedorovAvtomat();
                break;
            case Ribeyrolles::NAME:
                return new Ribeyrolles();
                break;

            //Shotgun
            case M1897::NAME:
                return new M1897();
                break;
            case Model10A::NAME:
                return new Model10A();
                break;
            case Automatic12G::NAME:
                return new Automatic12G();
            case Model1900::NAME:
                return new Model1900();
                break;

            //SniperRifle
            case SMLEMK3::NAME:
                return new SMLEMK3();
                break;
            case Gewehr98::NAME:
                return new Gewehr98();
                break;
            case MartiniHenry::NAME:
                return new MartiniHenry();
                break;
            case VetterliVitali::NAME:
                return new VetterliVitali();
                break;
            case GewehrM95::NAME:
                return new GewehrM95();
                break;

            //SMG
            case MP18::NAME:
                return new MP18();
                break;
            case Automatico::NAME:
                return new Automatico();
                break;
            case Hellriegel1915::NAME:
                return new Hellriegel1915();
                break;
            case FrommerStopAuto::NAME:
                return new FrommerStopAuto();
                break;

            //LMG
            case LewisGun::NAME:
                return new LewisGun();
                break;
            case Chauchat::NAME:
                return new Chauchat();
                break;
            case MG15::NAME:
                return new MG15();
                break;
            case BAR1918::NAME:
                return new BAR1918();
                break;

            //LMG
            case ColtSAA::NAME:
                return new ColtSAA();
                break;
            case RevolverMk6::NAME:
                return new RevolverMk6();
                break;
            case No3Revolver::NAME:
                return new No3Revolver();
                break;
            case NagantRevolver::NAME:
                return new NagantRevolver();
                break;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAssaultRifles(): array {
        return $this->ar;
    }

    /**
     * @return array
     */
    public function getHandguns(): array {
        return $this->hg;
    }

    /**
     * @return array
     */
    public function getShotguns(): array {
        return $this->sg;
    }

    /**
     * @return array
     */
    public function getSniperRifles(): array {
        return $this->sn;
    }

    /**
     * @return array
     */
    public function getSMGs(): array {
        return $this->smg;
    }

    /**
     * @return array
     */
    public function getLMGs(): array {
        return $this->lmg;
    }

    /**
     * @return array
     */
    public function getRevolvers(): array {
        return $this->rv;
    }
}