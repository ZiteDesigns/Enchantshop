<?php

declare(strict_types=1);

namespace ZiteDesigns\EnchantShop;

use ZiteDesigns\EnchantShop\commands\ES;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class EnchantShop extends PluginBase {

    /** @var EnchantShop|null */
    public static ?EnchantShop $instance = null;

    /**
     * @return Config
     */
    public static function getData(): Config {
        return EnchantShop::getInstance()->getConfig();
    }
    
    public function getMoney($player): void {
        BedrockEconomyAPI::legacy()->getPlayerBalance(
    		"$player",
    		ClosureContext::create(
        		function (?int $balance): void {
            		var_dump($balance);
        		},
    		)
		);
        return $balance;
    }
    
    public function reduceMoney($player, $cost): void {
        BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
            "$player",
            $cost,
            ClosureContext::create(
                function (bool $wasUpdated): void {
                    var_dump($wasUpdated);
                },
            )
        );
    }

    /**
     * @return EnchantShop|null
     */
    public static function getInstance(): ?EnchantShop {
        return self::$instance;
    }

    public function onEnable(): void {
        self::$instance = $this;

        $this->saveDefaultConfig();

        Server::getInstance()->getCommandMap()->register("ZiteDesigns", new ES());
    }

}