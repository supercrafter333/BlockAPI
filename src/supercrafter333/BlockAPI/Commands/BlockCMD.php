<?php

namespace supercrafter333\BlockAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use supercrafter333\BlockAPI\BlockAPILoader;
use supercrafter333\BlockAPI\API\BlockAPI;

class BlockCMD extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct("block", "§bBlock a Player!", "/block <player> <time> <i/h/d/m/y> <reason>", ["blockplayer"]);
    }

    public function execute(CommandSender $s, string $commandLabel, array $args)
    {
        $config = new Config(BlockAPILoader::getInstance()->getDataFolder()."config.yml", 2);
        $prefix = $config->get("prefix");
        if ($s->hasPermission("blockapi.block.cmd")) {
            if ($s instanceof Player) {
                if (count($args) >= 4) {
                    $selected = BlockAPILoader::getInstance()->getServer()->getPlayerByPrefix($args[0]);
                    if ($selected != null) {
                        if (!is_numeric($args[1])) {
                            $s->sendMessage($prefix . $config->get("argument-two-was-not-a-number"));
                        } elseif (!$args[1] > 0) {
                            $s->sendMessage($prefix . $config->get("argument-two-must-bee-over-null"));
                        } else {
                            if ($args[2] == "i" || $args[2] == "min" || $args[2] == "minute" || $args[2] == "minutes" || $args[2] == "minuten") {
                                BlockAPI::getBlockManager($selected)->setBlockTime($args[1], "minutes");
                                BlockAPI::getBlockManager($selected)->setBlockReason($args[3]);
                                BlockAPI::getBlockManager($selected)->setBlocker($s);
                                $selected->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($selected)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($selected)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($selected)->getBlocker()], $config->get("you-was-blocked-screen-text"))))));
                            } elseif ($args[2] == "h" || $args[2] == "hour" || $args[2] == "hours" || $args[2] == "stunde" || $args[2] == "stunden") {
                                BlockAPI::getBlockManager($selected)->setBlockTime($args[1], "hours");
                                BlockAPI::getBlockManager($selected)->setBlockReason($args[3]);
                                BlockAPI::getBlockManager($selected)->setBlocker($s);
                                $selected->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($selected)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($selected)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($selected)->getBlocker()], $config->get("you-was-blocked-screen-text"))))));
                            } elseif ($args[2] == "d" || $args[2] == "day" || $args[2] == "day" || $args[2] == "day" || $args[2] == "tag" || $args[2] == "tage") {
                                BlockAPI::getBlockManager($selected)->setBlockTime($args[1], "days");
                                BlockAPI::getBlockManager($selected)->setBlockReason($args[3]);
                                BlockAPI::getBlockManager($selected)->setBlocker($s);
                                $selected->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($selected)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($selected)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($selected)->getBlocker()], $config->get("you-was-blocked-screen-text"))))));
                            } elseif ($args[2] == "m" || $args[2] == "moth" || $args[2] == "months" || $args[2] == "monat" || $args[2] == "monate") {
                                BlockAPI::getBlockManager($selected)->setBlockTime($args[1], "moths");
                                BlockAPI::getBlockManager($selected)->setBlockReason($args[3]);
                                BlockAPI::getBlockManager($selected)->setBlocker($s);
                                $selected->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($selected)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($selected)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($selected)->getBlocker()], $config->get("you-was-blocked-screen-text"))))));
                            } elseif ($args[2] == "y" || $args[2] == "Y" || $args[2] == "Year" || $args[2] == "year" || $args[2] == "Years" || $args[2] == "years" || $args[2] == "jahr" || $args[2] == "jahre") {
                                BlockAPI::getBlockManager($selected)->setBlockTime($args[1], "years");
                                BlockAPI::getBlockManager($selected)->setBlockReason($args[3]);
                                BlockAPI::getBlockManager($selected)->setBlocker($s);
                                $selected->kick(str_replace(["{line}"], ["\n"], str_replace(["{unblockdate}"], [BlockAPI::getBlockManager($selected)->getBlockTime()], str_replace(["{reason}"], [BlockAPI::getBlockManager($selected)->getBlockReason()], str_replace(["{blocker}"], [BlockAPI::getBlockManager($selected)->getBlocker()], $config->get("you-was-blocked-screen-text"))))));
                            } else {
                                $s->sendMessage($prefix . $config->get("false-date-format"));
                            }
                        }
                    } else {
                        $s->sendMessage($prefix . str_replace(["{invalid_player}"], [$args[0]], $config->get("player-not-found")));
                    }
                } else {
                    $s->sendMessage($prefix . "§4Usage: §r/block <player> <time> <i/h/d/m/y> <reason>");
                }
            } else {
                $s->sendMessage($prefix . $config->get("only-In-Game"));
            }
        } else {
            $s->sendMessage($prefix . "no-permissions-to-use");
        }
    }
}