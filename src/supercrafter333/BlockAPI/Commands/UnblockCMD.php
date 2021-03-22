<?php

namespace supercrafter333\BlockAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;
use supercrafter333\BlockAPI\API\BlockAPI;

class UnblockCMD extends Command implements PluginIdentifiableCommand
{

    private $plugin;

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        $this->plugin = BlockAPILoader::getInstance();
        parent::__construct("unblock", "§bUnlock a Player!", "/unblock <playername>", ["unblockplayer"]);
    }

    public function execute(CommandSender $s, string $commandLabel, array $args)
    {
        $config = new Config(BlockAPILoader::getInstance()->getDataFolder() . "config.yml", 2);
        $prefix = $config->get("prefix");
        if ($s->hasPermission("blockapi.unblock.cmd")) {
            if (count($args) >= 1) {
                if (BlockAPI::getUnblockManager($args[0])->unBlock() == true) {
                    $s->sendMessage($prefix . str_replace(["{name}"], [$args[0]], $config->get("successful-unblocked")));
                    $this->getPlugin()->getLogger()->warning($prefix . str_replace(["{name}"], [$args[0]], $config->get("console-player-is-unblocked")));
                } else {
                    $s->sendMessage($prefix . str_replace(["{name}"], [$args[0]], $config->get("player-is-not-blocked")));
                }
            }
        } else {
            $s->sendMessage($prefix . $config->get("no-permissions-to-use"));
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}