<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class UnBlockAPI
{
    
    public $name;
    protected $config;

    public function __construct(string $playername)
    {
        $this->name = $playername;
        $this->config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml", Config::YAML);
    }

    public static function getUnBlockConfigurationManager(string $player): Config
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
            $exitsdate = new DateTime(UnBlockAPI::getUnBlockConfigurationManager($name)->get("date"));
            if ($date >= $exitsdate) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}