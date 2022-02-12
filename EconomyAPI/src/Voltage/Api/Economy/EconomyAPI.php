<?php
namespace Voltage\Api\Economy;

use Voltage\Api\Economy\provider\ProviderBase;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Voltage\Api\Economy\utils\Api;

class EconomyAPI extends PluginBase
{
    private static Config $config;
    private static ProviderBase $provider;
    private static Api $api;

    public static function getData() : Config{
        return self::$config;
    }

    public static function getProviderSysteme() : ?ProviderBase {
        return self::$provider;
    }

    public static function getApi() : ?Api {
        return self::$api;
    }

    public function onEnable() : void {
        @mkdir($this->getDataFolder());
        $this->getLogger()->notice("Loading the Economy plugin");
        $this->initConfig();
        $this->initProvider();
        $this->initApi();
    }

    public function initApi() : void {
        $this->getLogger()->info("Loading the Api system");
        self::$api = new Api($this);
    }

    public function initConfig() : void {
        if(!file_exists($this->getDataFolder()."config.yml")) {
            $this->getLogger()->notice("Add config file");
            $this->saveResource('config.yml');
        }
        self::$config = new Config($this->getDataFolder().'config.yml', Config::YAML);
    }

    public function initProvider() : void {
        $this->getLogger()->info("Loading the Provider system");
        switch (strtolower($this->getConfig()->get("database-provider"))) {
            case "mysql":
                break;
            case "json":
                $this->getLogger()->notice("The assigned provider is JSON");
                self::$provider = new Json($this);
                self::$provider->load();
                break;
            case "yaml":
                /*
                $this->getLogger()->notice("The assigned provider is YAML");
                self::$provider = new Yaml($this);
                self::$provider->load();
                */
                break;
            case "sqlite3":
                break;
            default:
                $this->getLogger()->critical("The provider system could not be loaded because it was not found");
                $this->getLogger()->notice("The assigned provider is JSON");
                self::$provider = new Json($this);
                self::$provider->load();
                break;
        }
    }
}