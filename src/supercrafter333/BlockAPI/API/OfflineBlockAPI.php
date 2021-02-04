<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class OfflineBlockAPI
{

    public $name;
    protected $config;

    public function __construct(string $playername)
    {
        $this->name = $playername;
        $this->config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml", Config::YAML);
    }

    public static function getOfflineConfigurationManager(string $player): Config
    {
        return new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
    }

    public function unBlock(): bool
    {
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml")) {
            unlink(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml");
            return true;
        } else {
            return false;
        }
    }

    public function checkBlockStatus(string $name): bool
    {
        $date = new DateTime("now");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $name . ".yml")) {
            $exitsdate = new DateTime(OfflineBlockAPI::getOfflineConfigurationManager($name)->get("date"));
            if ($date >= $exitsdate) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function setBlockTime(int $amount, string $timeformat)
    {
        $bantime = new DateTime('+' . $amount . ' ' . $timeformat);
        $date = new DateTime("now");
        if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml")) {
            $exitsdate = new DateTime($this->config->get("date"));
            if ($date >= $exitsdate) {
                BlockAPI::getUnblockManager($this->name)->unBlock();
            } else {
                $this->config->set("date", $bantime->format("Y-m-d H:i:s"));
                $this->config->save();
            }
        }
    }

    public function setBlocker(string $name)
    {
        $this->config->set("blocker", $name);
        $this->config->save();
    }

    public function setBlockReason(string $reason)
    {
        $this->config->set("reason", $reason);
        $this->config->save();
    }

    public function getBlockReason()
    {
        return $this->config->get("reason");
    }

    public function getBlocker()
    {
        return $this->config->get("blocker");
    }

    public function getBlockTime()
    {
        return $this->config->get("date");
    }
}