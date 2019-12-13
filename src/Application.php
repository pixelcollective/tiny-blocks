<?php

namespace TinyBlocks;

use DI\ContainerBuilder;

/**
 * Application
 *
 * @package TinyBlocks
 */
class Application
{
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
     * @param string filepath of app config
     */
    public function __construct(string $applicationConfig)
    {
        return $this->container = (new ContainerBuilder)
            ->addDefinitions($applicationConfig)
            ->build();
    }

    /**
     * Get singleton instance
     *
     * @param  string filepath of app config
     * @return \TinyBlocks\Application
     */
    public static function getInstance(string $applicationConfig) : \TinyBlocks\Application
    {
        if (! self::$instance) {
            self::$instance = new Application($applicationConfig);
        }

        return self::$instance;
    }

    /**
     * Get container
     *
     * @return \DI\Container
     */
    public function getContainer() : \DI\Container
    {
        return $this->container;
    }
}
