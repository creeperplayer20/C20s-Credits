<?php

declare(strict_types=1);

namespace creeperplayer20\credits\utils;

use creeperplayer20\credits\Main;
use pocketmine\player\Player;

class Functions {
    private $main;

    public function __construct() {
        $this->main = Main::getInstance();
    }

    public function getConfigValue(string $dataKey) {
        return $this->main->getConfig()->get($dataKey);
    }
    
    public function getPrefix(): string
    {
        return $this->getConfigValue("prefix");
    }

    /**
     * @param string $player
     * @return Player
     */
    function convertToPlayer(string $player): Player
    {
        return $this->main->getServer()->getPlayerByPrefix($player);
    }

    public function replace(string $string, array $replace): string
    {
        foreach ($replace as $key => $value) {
            $string = str_replace($key, $value, $string);
        }
        return $this->getPrefix() . $string;
    }
    
    public function checkIfPlayerExists(string $player) {
        if(file_exists($this->main->getDataFolder() . "players/$player.json")) return true;
        return false;
    }
}