<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\Player;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class BlockAPI
{

    public $player;
    protected $config;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player->getName() . ".yml", Config::YAML);
    }

    public static function getBlockManager(Player $player)
    {
        return new BlockAPI($player);
    }

    public static function getUnblockManager(string $name)
    {
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
        $bantime = new DateTime('+' . $amount . ' ' . $timeformat);
        $date = new DateTime("now");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->getPlayerName() . ".yml")) {
            $exitsdate = new DateTime($this->config->get("date"));
            if ($date >= $exitsdate) {
                BlockAPI::getUnblockManager($this->getPlayerName())->unBlock();
            } else {
                $this->config->set("date", $bantime->format("Y-m-d H:i:s"));
                $this->config->save();
            }
        }
    }

    public function setBlockReason(string $reason)
    {
        $this->config->set("reason", $reason);
        $this->config->save();
    }

    public function checkBlockStatus(Player $player): bool
    {
        $date = new DateTime("now");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player->getName() . ".yml")) {
            $exitsdate = new DateTime($this->config->get("date"));
            if ($date >= $exitsdate) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function setBlocker(Player $player)
    {
        $this->config->set("blocker", $player->getName());
        $this->config->save();
    }

    public function getBlockTime()
    {
        return $this->config->get("date");
    }

    public function getBlockReason()
    {
        return $this->config->get("reason");
    }

    public function getBlocker()
    {
        return $this->config->get("blocker");
    }
}
