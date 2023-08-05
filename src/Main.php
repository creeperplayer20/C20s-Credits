<?php

declare(strict_types=1);

namespace creeperplayer20\credits;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use creeperplayer20\credits\commands\CreditsCommand;
use creeperplayer20\credits\events\JoinEvent;
use creeperplayer20\credits\utils\Manager;

class Main extends PluginBase implements Listener
{
    private $manager;
    // Singleton pattern to get instance of Main class
    private static self $instance;
    protected function onLoad(): void
    {
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    // Get instance of Manager class
    public function getManager()
    {
        return $this->manager;
    }

    // Register commands
    public function onEnable(): void
    {
        @mkdir($this->getDataFolder() . "players/");

        $this->manager = new Manager($this);

        $this->getServer()->getCommandMap()->register("credits", new CreditsCommand("credits", "Shows the credits of the plugin", "/credits", ["cr"]));
        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent(), $this);
    }

    public function getCredits(string $player)
    {
        return $this->getManager()->getCredits($player);
    }

    public function addCredits(string $player, int $amount)
    {
        $this->getManager()->addCredits($player, $amount);
    }

    public function reduceCredits(string $player, int $amount)
    {
        $this->getManager()->reduceCredits($player, $amount);
    }

    public function setCredits(string $player, int $amount)
    {
        $this->getManager()->setCredits($player, $amount);
    }

    public function resetCredits(string $player)
    {
        $this->getManager()->resetCredits($player);
    }
}