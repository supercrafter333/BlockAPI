<?php

namespace supercrafter333\BlockAPI\API;

use DateTime;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;

class OffBlockAPI
{

    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function getExtraConfigurationManager(string $player): Config
    {
        return new Config(BlockAPILoader::getInstance()->getDataFolder() . "players/" . $player . ".yml");
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
            $exitsdate = new DateTime(OffBlockAPI::getExtraConfigurationManager($name)->get("date"));
            if ($date >= $exitsdate) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}