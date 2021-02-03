<?php

namespace supercrafter333\BlockAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\player\Player;
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
        $config = new Config($this->getDataFolder() . "config.yml");
        $this->config = $config;
        if (!$config->exists("version") && !$config->get("version") == "1.1.0") {
            $this->getServer()->getLogger()->critical("!!YOUR CONFIGURATION FILE IS OUTDATED!! Please delete the file config.yml and restart your server!");
            $pluginMgr->disablePlugin($this);
        }
        $pluginMgr->registerEvents($this, $this);
        @mkdir($this->getDataFolder() . "players/");
        $cmdMap = $server->getCommandMap();
        $cmdMap->registerAll("BlockAPI",
            [
                new checkblockstatusCMD("checkblockstatus"),
                new UnblockCMD("unblock"),
                new BlockCMD("block")
            ]);
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $playerx = $event->getPlayerInfo();
        $name = $playerx->getUsername();
        $player = $this->getServer()->getPlayerExact($name);
        if (BlockAPI::getUnBlockManager($name)->checkBlockStatus($name) == true) {
            $this->getLogger()->warning("This player is banned!");
            $eventpk = new PlayerKickEvent($player, str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))), true);
            //$player->disconnect(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))), true);
            $player->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))));
        } else {
            BlockAPI::getUnblockManager($name)->unBlock();
        }
    }
}