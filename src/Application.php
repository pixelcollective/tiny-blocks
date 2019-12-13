<?php

namespace TinyBlocks;

use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;
use Illuminate\Support\Collection;

/**
 * Application
 *
 * @package TinyBlocks
 */
class Application
{
    /**
     * Core configuration files
     * @var array
     */
    public static $configFiles = [
        'filesystem',
        'providers',
    ];

    /**
     * The application instance.
     *
     * @static \TinyBlocks\Application
     */
    public static $instance;

    /**
     * The dependency injection container.
     *
     * @var \DI\Container
     */
    public $container;

    /**
     * Class constructor.
     *
     * @param string filepath of override configs
     */
    public function __construct(string $config = null)
    {
        return $this->container = (new ContainerBuilder)
            ->addDefinitions($this->getConfig($config))
            ->build();
    }

    /**
     * Get singleton instance
     *
     * @param  string filepath of override configs
     * @return \TinyBlocks\Application
     */
    public static function getInstance(string $config = null) : \TinyBlocks\Application
    {
        if (! self::$instance) {
            self::$instance = new Application($config);
        }

        return self::$instance;
    }

    /**
     * Get container
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }

    /**
     * Get configuration
     *
     * @param  array filepath of override configs
     * @return array
     */
    public function getConfig($configOverride = null) : array
    {
        $config = ! $configOverride
            ? Collection::make(self::$configFiles)->mapWithKeys(function ($file) {
                return $this->requireCoreConfigFile($file);
            })
            : Collection::make(glob("{$configOverride}/*.php"))->mapWithKeys(function ($file) {
                return require $file;
            });

        if ($configOverride) {
            Collection::make(self::$configFiles)->each(function ($file) use ($config) {
                if (! $config->get($file)) {
                    $config->put($file, $this->requireCoreConfigFile($file));
                }
            });
        }

        return $config->toArray();
    }

    /**
     * Require core configuration file
     *
     * @param  string file
     * @return array
     */
    public function requireCoreConfigFile(string $file) : array
    {
        return require realpath(__DIR__ . "/../config/{$file}.php");
    }
}
