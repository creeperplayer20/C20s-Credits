<?php

declare(strict_types=1);

namespace creeperplayer20\credits\events;

use creeperplayer20\credits\events\CreditsEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use creeperplayer20\credits\Main;
use pocketmine\utils\Config;

class JoinEvent extends CreditsEvent implements Listener
{
    private $main;

    public function __construct() {
        $this->main = Main::getInstance();
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        
        if(!file_exists($this->main->getDataFolder() . "players/" . $player->getName() . ".json")) {
            $config = new Config($this->main->getDataFolder() . "players/" . $player->getName() . ".json", Config::JSON);
            $config->set("credits", $this->main->getConfig()->get("default-credits"));
            $config->save();
        }
    }
}