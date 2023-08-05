<?php

declare(strict_types=1);

namespace creeperplayer20\credits\utils;

use pocketmine\utils\Config;

use creeperplayer20\credits\Main;
use creeperplayer20\credits\utils\Functions;

class Manager
{
    private $main, $functions;

    public function __construct()
    {
        $this->main = Main::getInstance();
        $this->functions = new Functions();
    }

    public function getCredits(string $player = null): int
    {
        if (!$this->functions->checkIfPlayerExists($player))
            return false;

        $config = new Config($this->main->getDataFolder() . "players/$player.json", Config::JSON);
        return $config->get("credits");
    }

    public function addCredits(string $player, int $amount)
    {
        if (!$this->functions->checkIfPlayerExists($player))
            return false;

        $this->functions->setPlayerData($player, $this->getCredits($player) + $amount);

        $playerObject = $this->functions->convertToPlayer($player);

        if ($playerObject == null)
            return true;

        if ($playerObject->isOnline() && (bool) $this->functions->getConfigValue("credits-add-broadcast"))
            $this->functions->convertToPlayer($player)->sendMessage(
                $this->functions->replace(
                    $this->functions->getConfigValue("credits-add-message"),
                    ["{player}" => $player, "{credits}" => (string) $amount]
                )
            );

        return true;
    }

    public function reduceCredits(string $player, int $amount)
    {
        if (!$this->functions->checkIfPlayerExists($player))
            return false;

        $this->functions->setPlayerData($player, $this->getCredits($player) - $amount);

        $playerObject = $this->functions->convertToPlayer($player);

        if ($playerObject == null)
            return true;

        if ($playerObject->isOnline() && (bool) $this->functions->getConfigValue("credits-reduce-broadcast"))
            $this->functions->convertToPlayer($player)->sendMessage(
                $this->functions->replace(
                    $this->functions->getConfigValue("credits-reduce-message"),
                    ["{player}" => $player, "{credits}" => (string) $amount]
                )
            );

        return true;
    }

    public function setCredits(string $player, int $amount)
    {
        if (!$this->functions->checkIfPlayerExists($player))
            return false;

        $this->functions->setPlayerData($player, $amount);

        $playerObject = $this->functions->convertToPlayer($player);

        if ($playerObject == null)
            return true;

        if ($playerObject->isOnline() && (bool) $this->functions->getConfigValue("credits-set-broadcast"))
            $this->functions->convertToPlayer($player)->sendMessage(
                $this->functions->replace(
                    $this->functions->getConfigValue("credits-set-message"),
                    ["{player}" => $player, "{credits}" => (string) $amount]
                )
            );

        return true;
    }

    public function resetCredits(string $player)
    {
        if (!$this->functions->checkIfPlayerExists($player))
            return false;

        $this->functions->setPlayerData($player, $this->functions->getConfigValue("default-credits"));

        $playerObject = $this->functions->convertToPlayer($player);

        if ($playerObject == null)
            return true;

        if ($playerObject->isOnline() && (bool) $this->functions->getConfigValue("credits-reset-broadcast"))
            $this->functions->convertToPlayer($player)->sendMessage(
                $this->functions->replace(
                    $this->functions->getConfigValue("credits-reset-message"),
                    ["{player}" => $player]
                )
            );

        return true;
    }
}