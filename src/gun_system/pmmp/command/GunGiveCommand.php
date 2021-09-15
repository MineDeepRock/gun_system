<?php


namespace gun_system\pmmp\command;


use gun_system\GunSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class GunGiveCommand extends Command
{
    public function __construct() {
        parent::__construct("gungive", "", "");
        $this->setPermission("gungive");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool {
        if (count($args) === 0) {
            $sender->sendMessage("/gungive name");
            return true;
        }
        if ($sender instanceof Player) {
            $itemGun = GunSystem::getItemGun($args[0]);
            $sender->getInventory()->addItem($itemGun);
            return true;
        }


        return true;
    }
}