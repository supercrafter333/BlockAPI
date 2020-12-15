<?php

namespace supercrafter333\BlockAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\API\OffBlockAPI;
use supercrafter333\BlockAPI\BlockAPILoader;
use supercrafter333\BlockAPI\API\BlockAPI;

class checkblockstatusCMD extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct("checkblockstatus", "§bCheck block status of a player!", "/checkblockstatus <playername>", $aliases);
    }

    public function execute(CommandSender $s, string $commandLabel, array $args)
    {
        $config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "config.yml", 2);
        $prefix = $config->get("prefix");
        if ($s->hasPermission("blockapi.checkblockstatus.cmd")) {
            if (count($args) >= 1) {
                if (BlockAPI::getUnblockManager($args[0])->checkBlockStatus($args[0]) == true) {
                    $s->sendMessage($prefix . str_replace(["{name}"], [$args[0]], $config->get("player-is-blocked")));
                } else {
                    $s->sendMessage($prefix . str_replace(["{name}"], [$args[0]], $config->get("player-is-not-blocked")));
                }
            }
        } else {
            $s->sendMessage($prefix . $config->get("no-permissions-to-use"));
        }
    }
}