<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\OfflinePlayer;
use pocketmine\Player;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class BlockAPI
{

    public $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public static function getConfigurationManager(Player $player): Config
    {
        $config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player->getName() . ".yml", 2);
        return $config;
    }

    public static function getBlockManager(Player $player) {
        return new BlockAPI($player);
    }

    public static function getUnblockManager(string $name) {
        return new OffBlockAPI($name);
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getPlayerName()
    {
        return $this->player->getName();
    }

    public function setBlockTime(int $amount, string $timeformat)
    {
        $bantime = new DateTime("+" . $amount . $timeformat);
        $bantime->format("Y-m-d H:i");
        $date = new DateTime("now");
        $date->format("Y-m-d H:i");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->getPlayerName() . ".yml")) {
            $exitsdate = new DateTime(BlockAPI::getConfigurationManager($this->player)->get("date"));
            if ($date >= $exitsdate) {
                BlockAPI::getUnblockManager($this->getPlayerName())->unBlock();
            } else {
                BlockAPI::getConfigurationManager($this->player)->set("date", $bantime);
                BlockAPI::getConfigurationManager($this->player)->save();
            }
        }
    }

    public function setBlockReason(string $reason)
    {
            BlockAPI::getConfigurationManager($this->player)->set("reason", $reason);
            BlockAPI::getConfigurationManager($this->player)->save();
    }

    public function checkBlockStatus(Player $player): bool
    {
        $date = new DateTime("now");
        $date->format("Y-m-d H:i");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player->getName() . ".yml")) {
            $exitsdate = new DateTime(BlockAPI::getConfigurationManager($player)->get("date"));
            if ($date >= $exitsdate) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function setBlocker(Player $player) {
        BlockAPI::getConfigurationManager($this->player)->set("blocker", $player->getName());
        BlockAPI::getConfigurationManager($this->player)->save();
    }

    public function getBlockTime() {
        return BlockAPI::getConfigurationManager($this->player)->get("date");
    }

    public function getBlockReason() {
        return BlockAPI::getConfigurationManager($this->player)->get("reason");
    }

    public function getBlocker() {
        return BlockAPI::getConfigurationManager($this->player)->get("blocker");
    }
}