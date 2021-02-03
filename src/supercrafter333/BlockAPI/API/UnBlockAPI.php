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
        $player = BlockAPILoader::getInstance()->getServer()->getPlayerExact($name);
        if ($player !== null) {
            $namex = $player->getName();
            $date = new DateTime("now");
            if (file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $namex . ".yml")) {
                if ($this->config->exists("date")) {
                    $exitsdate = new DateTime($this->config->get("date"));
                    if ($date >= $exitsdate) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}