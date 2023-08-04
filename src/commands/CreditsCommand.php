<?php

declare(strict_types=1);

namespace creeperplayer20\credits\commands;

use pocketmine\command\{Command, CommandSender};
use creeperplayer20\credits\Main;
use creeperplayer20\credits\utils\{Functions, Manager};

class CreditsCommand extends Command 
{
    private $main, $manager, $functions;

    public function __construct(string $name, string $description, string $usageMessage, array $aliases = []) 
    {
        $this->main = Main::getInstance();
        $this->manager = new Manager();
        $this->functions = new Functions();

        parent::__construct($name, $description, $usageMessage, $aliases);

        $permissions = [
            "c20s-credits.use",
            "c20s-credits.check",
            "c20s-credits.add",
            "c20s-credits.reduce",
            "c20s-credits.set",
            "c20s-credits.reset",
            "c20s-credits.reload"
        ];

        foreach($permissions as $permission) {
            $this->setPermission($permission);
        }

        $this->setPermissionMessage($this->functions->getPrefix() . "§cYou do not have permission to use this command§f!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if(!$sender->checkForPermission($sender, "c20s-credits.use")) return false;

        if(empty($args)) {
            $sender->sendMessage($this->functions->getPrefix() . "§cUsage: /credits <check|add|reduce|set|reset|reload>");
            return true;
        }

        switch($args[0]) {
            case "check":
                if(!$sender->checkForPermission($sender, "c20s-credits.check")) return false;

                if(!isset($args[1])) {
                    $sender->sendMessage($this->functions->replace(
                        $this->functions->getConfigValue("credits-check-message"), 
                        ["{credits}" => (string)$this->manager->getCredits($sender->getName())])
                    );
                    return true;
                }
                
                if($this->functions->checkIfPlayerExists($args[1])) {
                    $sender->sendMessage($this->functions->replace(
                        $this->functions->getConfigValue("credits-check-other-message"), 
                        ["{credits}" => (string)$this->manager->getCredits($args[1]), "{player}" => $args[1]])
                    );
                    return true;
                }

                $sender->sendMessage($this->functions->getPrefix() . "§cPlayer not found§f!");
            return false;
            case "add":
                if(!$sender->checkForPermission($sender, "c20s-credits.add")) return false;

                if(!isset($args[1]) || !isset($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cUsage: /credits add <player> <amount>");
                    return false;
                }

                if(!is_numeric($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cAmount must be a number§f!");
                    return false;
                }

                if($args[2] <= 0) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cAmount must be greater than 0§f!");
                    return false;
                }

                if($this->manager->addCredits($args[1], (int)$args[2])) {
                    if($this->functions->getConfigValue("credits-add-issuer-broadcast")) 
                        $sender->sendMessage($this->functions->replace(
                            $this->functions->getConfigValue("credits-add-issuer-message"), 
                            ["{player}" => $args[1], "{credits}" => (string)$args[2]])
                        );
                    return true;
                }

                $sender->sendMessage($this->functions->getPrefix() . "§cPlayer not found§f!");
            return false;
            case "reduce":
                if(!$sender->checkForPermission($sender, "c20s-credits.reduce")) return false;

                if(!isset($args[1]) || !isset($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cUsage: /credits reduce <player> <amount>");
                    return false;
                }

                if(!is_numeric($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cAmount must be a number§f!");
                    return false;
                }

                if($args[2] <= 0) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cAmount must be greater than 0§f!");
                    return false;
                }

                if($this->manager->reduceCredits($args[1], (int)$args[2])) {
                    if($this->functions->getConfigValue("credits-reduce-issuer-broadcast")) 
                        $sender->sendMessage($this->functions->replace(
                            $this->functions->getConfigValue("credits-reduce-issuer-message"), 
                            ["{player}" => $args[1], "{credits}" => (string)$args[2]])
                        );
                    return true;
                }

                $sender->sendMessage($this->functions->getPrefix() . "§cPlayer not found§f!");
            return false;
            case "set":
                if(!$sender->checkForPermission($sender, "c20s-credits.set")) return false;

                if(!isset($args[1]) || !isset($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cUsage: /credits set <player> <amount>");
                    return false;
                }

                if(!is_numeric($args[2])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cAmount must be a number§f!");
                    return false;
                }

                if($this->manager->setCredits($args[1], (int)$args[2])) {
                    if($this->functions->getConfigValue("credits-set-issuer-broadcast")) 
                        $sender->sendMessage($this->functions->replace(
                            $this->functions->getConfigValue("credits-set-issuer-message"), 
                            ["{player}" => $args[1], "{credits}" => (string)$args[2]])
                        );
                    return true;
                }

                $sender->sendMessage($this->functions->getPrefix() . "§cPlayer not found§f!");
            return false;
            case "reset":
                if(!$sender->checkForPermission($sender, "c20s-credits.reset")) return false;

                if(!isset($args[1])) {
                    $sender->sendMessage($this->functions->getPrefix() . "§cUsage: /credits reset <player>");
                    return false;
                }

                if($this->manager->resetCredits($args[1])) {
                    if($this->functions->getConfigValue("credits-reset-issuer-broadcast")) 
                        $sender->sendMessage($this->functions->replace(
                            $this->functions->getConfigValue("credits-reset-issuer-message"), 
                            ["{player}" => $args[1]])
                        );
                    return true;
                }

                $sender->sendMessage($this->functions->getPrefix() . "§cPlayer not found§f!");
            return false;
            case "reload":
                if(!$sender->checkForPermission($sender, "c20s-credits.reload")) return false;

                $this->main->reloadConfig();
                $sender->sendMessage($this->functions->getPrefix() . "§aConfig reloaded§f!");
            return true;
        }

        return false;
    }
}