<?php


namespace gun_system\service;


use gun_system\GunSystem;
use gun_system\model\Gun;

class GenerateGunDataListFilesService
{
    static function execute(string $path) {

        $textList = [];

        /** @var Gun $gun */
        foreach (GunSystem::loadAllGuns() as $gun) {
            if (!key_exists($gun->getType()->getTypeText(), $textList)) {
                $textList[$gun->getType()->getTypeText()] = "";
            }

            $str = GunSystem::getGunDescription($gun);
            $str = str_replace("§a■", "![#00ff00](https://placehold.it/15/00ff00/000000?text=+)", $str);
            $str = str_replace("§f■", "![#dbdbdb](https://placehold.it/15/dbdbdb/000000?text=+)", $str);
            $str = str_replace("§f", "", $str);
            $str .= "\n距離減衰のグラフ\n![{$gun->getName()}](https://raw.githubusercontent.com/MineDeepRock/MineDeepRock.github.io/master/data/{$gun->getName()}.png)";
            $str = str_replace("\n","  \n",$str);

            $textList[$gun->getType()->getTypeText()] .= $str . "\n";
        }

        foreach ($textList as $key => $value) {
            file_put_contents("{$path}/{$key}.md", $value);
        }
    }
}