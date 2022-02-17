<?php
namespace Voltage\Api\Economy;

use Voltage\Api\Economy\listener\EconomyListener;
use Voltage\Api\Economy\provider\lists\Json;
use Voltage\Api\Economy\provider\lists\Yaml;
use Voltage\Api\Economy\provider\ProviderBase;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Voltage\Api\Economy\utils\Api;

class EconomyAPI extends PluginBase
{
    private static Config $config;
    private static Api $api;
    private static array $providers = [];

    public static function getData() : Config{
        return self::$config;
    }

    public static function createProvider(ProviderBase $provider) : void {
        self::$providers[$provider->getEconomyName()] = $provider;
        if (!self::getData()->exists("providers")) {
            if (!isset(self::getData()->get("providers")[$provider->getName()])) {
                $data = self::getData()->get("providers");
                $data[$provider->getEconomyName()] =
                    [
                        "default" => $provider->getBaseMoney(),
                        "max" => $provider->getMaxMoney(),
                        "database-provider" => $provider->getName()
                    ];
                self::getData()->set("providers", $data);
            }
        }
    }

    public static function getProvider(string $name = "default") : ?ProviderBase {
        $prov = null;
        if (isset(self::$providers[strtolower($name)])) {
            $prov = self::$providers[strtolower($name)];
        }
        return $prov;
    }

    public static function getProviderSysteme() : ?ProviderBase {
        return self::getProvider();
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
        new EconomyListener($this);
    }

    public function initApi() : void {
        $this->getLogger()->info("Loading the Api system");
        self::$api = new Api($this);
    }

    public function initConfig() : void {
        if(!file_exists($this->getDataFolder()."config.yml")) {
            $this->getLogger()->notice("Add config file");
            $this->saveResource("config.yml");
        }
        self::$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
    }

    private function initProvider() : void {
        $this->getLogger()->info("Loading the Provider system");
        self::createProvider($this->getProviderData("default",strtolower($this->getConfig()->get("database-provider")),EconomyAPI::getData()->get("default"),EconomyAPI::getData()->get("max")));

        $this->getLogger()->notice("Loading all providers");
        if (self::getData()->exists("providers")) {
            if (is_array(self::getData()->get("providers"))) {
                foreach (self::getData()->get("providers") as $name => $data) {
                    if ($name !== "default") {
                        $default = null;
                        $max = null;
                        $provider = null;
                        if (isset($data["default"])) {
                            $default = $data["default"];
                        }
                        if (isset($data["max"])) {
                            $max = $data["max"];
                        }
                        if (isset($data["database-provider"])) {
                            $provider = $data["database-provider"];
                        }
                        self::createProvider($this->getProviderData($name, $provider, $default, $max));
                    }
                }
            }
        }
    }

    private function getProviderData(string $name, ?string $provider = null, ?int $default = null, ?int $maxmoney = null) : ProviderBase
    {
        switch ($provider) {
            case "mysql":
                break;
            case "json":
                $providerdata = new Json($this, $name, $default, $maxmoney);
                $this->getLogger()->notice("The assigned provider is JSON");
                $providerdata->load();
                return $providerdata;
            case "yaml":
                $providerdata = new Yaml($this, $name, $default, $maxmoney);
                $this->getLogger()->notice("The assigned provider is YAML");
                return $providerdata;
            case "sqlite3":
                break;
            default:
                $providerdata = new Json($this, $name, $default, $maxmoney);
                $this->getLogger()->critical("The provider system could not be loaded because it was not found");
                $this->getLogger()->notice("The assigned provider is JSON");
                $providerdata->load();
                return $providerdata;
        }
    }

}