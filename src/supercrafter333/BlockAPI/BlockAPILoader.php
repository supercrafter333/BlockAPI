<?php

namespace supercrafter333\BlockAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\API\BlockAPI;
use supercrafter333\BlockAPI\Commands\BlockCMD;
use supercrafter333\BlockAPI\Commands\checkblockstatusCMD;
use supercrafter333\BlockAPI\Commands\UnblockCMD;

class BlockAPILoader extends PluginBase implements Listener
{

    public static $instance;
    public $config;

    public function onEnable()
    {
        $server = $this->getServer();
        $pluginMgr = $server->getPluginManager();
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder()."config.yml");
        $this->config = $config;
        if (!$config->exists("version") && !$config->get("version") == "1.0.0") {
            $this->getLogger()->error("!!YOUR CONFIGURATION FILE IS OUTDATED!! Please delete the file config.yml and restart your server!");
            $pluginMgr->disablePlugin($this);
        }
        $pluginMgr->registerEvents($this, $this);
        @mkdir($this->getDataFolder()."players/");
        $cmdMap = $server->getCommandMap();
        $cmdMap->registerAll("BlockAPILoader",
        [
            new checkblockstatusCMD("checkblockstatus"),
            new UnblockCMD("unblock"),
            new BlockCMD("block")
        ]);
        self::$instance = $this;
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function onPreLogin(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if(BlockAPI::getBlockManager($player)->checkBlockStatus($player) == true) {
            $player->close("", str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))));
            $event->setCancelled(true);
        } else {
            $event->setCancelled(false);
        }
    }
}