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
        self::$instance = $this;
        $server = $this->getServer();
        $pluginMgr = $server->getPluginManager();
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml");
        $this->config = $config;
        if (!$config->exists("version") && !$config->get("version") == "1.2.0") {
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
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (BlockAPI::getUnblockManager($name)->checkBlockStatus($name) == true) { //player is blocked
            $player->close("", str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))));
            $event->setCancelled(true);
        } else { //player is not or no longer blocked
            $event->setCancelled(false);
        }
    }

    /*public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $playerx = $event->getPlayerInfo();
        $lul = $playerx->getExtraData();
        $name = $playerx->getUsername();
        $player = $this->getServer()->getPlayerByPrefix($name);
        if ($player instanceof Player) {
            $this->kickPlayerTest($player, "test");
            if (BlockAPI::getUnBlockManager($name)->checkBlockStatus($name) == true) {
                $this->getLogger()->warning("This player is banned!");

                $eventpk = new PlayerKickEvent($player, str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))), true);
                //$player->disconnect(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))), true);
                $player->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text"))))));
            } else {
                BlockAPI::getUnblockManager($name)->unBlock();
            }
        }
    }*/


    //PM4 Codes, idk why this part is here but I'll don't remove it xDDDDDDD
    /*public function kickPlayerByDefaultReason(Player $player)
    {
        $reason = str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($player)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($player)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($player)->getBlocker()], $this->config->get("you-are-blocked-screen-text")))));
        KickMgr::getKickMgr()->kickPlayer($player, $reason);
    }

    public function kickPlayer(NetworkSession $session, string $reason, Player $player)
    {
        $kick = new DisconnectPacket();
        $kick->message = $reason;
        $session->sendDataPacket($kick);
    }

    public function kickPlayerTest(Player $player, string $reason)
    {
        $event = new PlayerKickEvent($player, $reason, $player->getLeaveMessage());
        $event->call();
    }*/
}