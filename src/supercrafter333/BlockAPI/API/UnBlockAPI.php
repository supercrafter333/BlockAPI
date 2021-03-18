<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class UnBlockAPI
{

    public $name;
    protected $config;
    protected $Xconfig;

    public function __construct(string $playername)
    {
        $this->name = $playername;
        $this->config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml", Config::YAML);
        $this->Xconfig = new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml");
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

    public function superUnBlock(): bool
    {
        return unlink(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $this->name . ".yml");
    }

    public function checkBlockStatus(string $name): bool
    {
        $date = new DateTime("now");
        if (!file_exists(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $name . ".yml")) {
            $this->superUnBlock();
            return false;
        } elseif (file_get_contents(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $name . ".yml") == false) {
            $this->superUnBlock();
            return false;
        } elseif ($date < new DateTime($this->Xconfig->get("date"))) {
            return true;
        } else {
            return false;
        }
    }
}