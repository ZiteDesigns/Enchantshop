<?php

declare(strict_types=1);

namespace ZiteDesigns\EnchantShop\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use ZiteDesigns\EnchantShop\forms\ESNormalForm;
use ZiteDesigns\EnchantShop\forms\ESBarForm;

class ES extends Command {

    public function __construct() {
        parent::__construct("eshop");
        $this->setDescription(C::BLUE . C::BOLD . " ⊱ " . C::RESET . C::GOLD . "Opens ZiteDesigns enchant shop");
        $this->setUsage("/enchshop");
        $this->setAliases(["enchshop"]);
        $this->setPermission(null);

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player) {
            $sender->sendMessage(C::YELLOW . "You are not a authorized player");
            return;
        }
        foreach(EnchantShop::getData()->get("shop")["shop-ui-type"] as $type){
            if ($type === "normal"){
                new ESNormalForm($sender);

                }else{
                    new ESBarForm($sender);
                }
            }
        }
    }
