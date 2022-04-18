<?php

declare(strict_types=1);

namespace ZiteDesigns\EnchantShop\forms;

use DaPigGuy\PiggyCustomEnchants\utils\Utils;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use ZiteDesigns\EnchantShop\libs\FormsUI\SimpleForm;
use ZiteDesigns\EnchantShop\EnchantShop;

class BuyConfirmationForm {

    public function __construct(Player $player, string $data) {
        $form = new SimpleForm(function (Player $player, string $return = null) use ($data) {
            if($return === null)
                return;

            if($return === "confirm"){

                $data = explode(":", $data);
                $enchantName = $data[0];
                $baseCost = $data[1];

                $item = $player->getInventory()->getItemInHand();

                $setLore = false;
				if(\DaPigGuy\PiggyCustomEnchants\CustomEnchantManager::getEnchantmentByName($enchantName) !== null) {
                    $enchant = \DaPigGuy\PiggyCustomEnchants\CustomEnchantManager::getEnchantmentByName($enchantName);
                } elseif(StringToEnchantmentParser::getInstance()->parse($enchantName) !== null) {
                    $enchant = StringToEnchantmentParser::getInstance()->parse($enchantName);
                }

                if($item->hasEnchantment($enchant->getId())) {
                    $level = $item->getEnchantment($enchant->getId())->getLevel();
                    $newLevel = $level + 1;
                    if($newLevel > $enchant->getMaxLevel()) {
                        $player->sendMessage(C::RED . "This enchant is already on its max lvl");
                        return;
                    }
                } else {
                    $newLevel = 1;
                }

                $cost = $baseCost + ($baseCost * 3 / 100 * ($newLevel - 1));

                if(EnchantShop::getMoney($player) < $cost) {
                    $player->sendMessage(C::RED . "You dont have enough money. You need $$cost");
                    return;
                }

                EnchantShop::reduceMoney($player, $cost);
               
            	if (!Utils::itemMatchesItemType($item, $enchant->getItemType())) {
                	$sender->sendMessage(C::RED . "You Switched slots to quick for me!");
                	return;
            	}elseif (Utils::itemMatchesItemType($item, $enchant->getItemType())) {
            	
                	$item->addEnchantment(new EnchantmentInstance($enchant, $newLevel));
                }
                if($setLore) {
                    $enchants = [];

                    foreach($item->getEnchantments() as $enchantment) {
                        if(array_search(EnchantmentIdMap::getInstance()->toId($enchantment->getType()), CustomEnchantManager::CONVERSION)) {
                            $name = $enchantment->getType()->getName();
                            $level = $enchantment->getLevel();
                            $enchants[] = C::RESET . C::GOLD . $name . " " . $level;
                        }
                    }

                    $item->setLore($enchants);
                }

                $player->getInventory()->setItemInHand($item);
                $player->sendMessage(C::GREEN . "Purchased enchant $enchantName of lvl $newLevel for $$cost");
            }
        });
        $form->setTitle(C::RED . "★ " . C::GOLD . "Enchant Shop" . C::RED . " ★");
        $form->addButton(C::GREEN . "Confirm", -1, "", "confirm");
        $form->addButton(C::RED . "Cancel", -1, "", "cancel");
        $player->sendForm($form);
        return $form;
    }

}