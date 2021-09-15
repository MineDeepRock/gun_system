<?php


namespace gun_system\pmmp\service;


use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendMessageService
{
    static function sendBulletCount(Player $player, int $currentBullet, int $remainingBullet): void {
        $player->sendPopup(TextFormat::BLUE . TextFormat::BOLD . $currentBullet . "\\" . TextFormat::RESET . TextFormat::BLUE . $remainingBullet);
    }

    static function sendReloadingMessage(Player $player): void {
        $player->sendTip("リロード中");
    }

    static function sendOverheatingMessage(Player $player): void {
        $player->sendTip("オーバーヒート中");
    }

    static function sendReloadingProgress(Player $player, int $magazineCapacity, float $spendSecond, float $necessarySecond): void {
        $progress = $spendSecond / $necessarySecond;
        if ($progress >= 1) {
            $text = str_repeat(TextFormat::GREEN . "|", $magazineCapacity);
        } else {
            $text = str_repeat(TextFormat::GREEN . "|", intval($magazineCapacity * $progress));
            $text .= str_repeat(TextFormat::WHITE . "|", intval($magazineCapacity - $magazineCapacity * $progress));
        }
        $player->sendPopup($text);
    }
}