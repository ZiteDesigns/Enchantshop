<?php

declare(strict_types=1);

namespace ZiteDesigns\EnchantShop\forms;

use pocketmine\item\Axe;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Hoe;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use ZiteDesigns\EnchantShop\EnchantShop;
use ZiteDesigns\EnchantShop\libs\FormsUI\SimpleForm;

class ESForm {

    const CATEGORIES = [
        Sword::class => "sword",
        Bow::class => "bow",
        Pickaxe::class => "pickaxe",
        Axe::class => "axe",
        Hoe::class => "hoe",
        Shovel::class => "shovel"];

    /**
     * @param Player $sender
     */
    public function __construct(Player $sender) {
        $item = $sender->getInventory()->getItemInHand();
        $type = null;
        foreach(array_keys(self::CATEGORIES) as $category) {
            if($item instanceof $category) {
                $type = self::CATEGORIES[$category];
            }
        }
        if($type === null) {
            foreach([
                        298,
                        302,
                        306,
                        310,
                        314] as $id) {
                if($item->getId() === $id)
                    $type = "helmet";
            }
            foreach([
                        299,
                        303,
                        307,
                        311,
                        315] as $id) {
                if($item->getId() === $id)
                    $type = "chestplate";
            }
            foreach([
                        300,
                        304,
                        308,
                        312,
                        316] as $id) {
                if($item->getId() === $id)
                    $type = "leggings";
            }
            foreach([
                        301,
                        305,
                        309,
                        313,
                        317] as $id) {
                if($item->getId() === $id)
                    $type = "boots";
            }
        }
        if($type === null)
            $type = "misc";
        $form = new SimpleForm(function(Player $player, string $data = null) {
            if($data === null)
                return;

            new BuyConfirmationESForm($player, $data);
        });
        $form->setTitle(C::RED . "★ " . C::GOLD . "Enchantment Shop" . C::RED . " ★");
        $form->setContent(C::GREEN . "Each enchantment is raised by 3% per level !!!
Credits to ZiteDesigns for this form!");
        foreach(EnchantShop::getData()->get("shop")[$type] as $key => $enchantData) {
            foreach($enchantData as $enchantName => $baseinfo) {
                $info = explode(":::", $baseinfo);
                $form->addButton(C::RED . "★ " . C::DARK_RED . $enchantName . "" . C::GRAY . "Cost: $" . $info[0] . C::RED . " ★\n" . C::BLACK . $info[1], -1, "", "$enchantName:" . $info[0]);
            }
        }
        $sender->sendForm($form);
        return $form;
    }
}
