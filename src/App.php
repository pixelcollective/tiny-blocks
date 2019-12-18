<?php

namespace TinyBlocks;

use Illuminate\Support\Collection;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ApplicationInterface as Application;
use TinyBlocks\Contracts\AssetsInterface as Assets;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\RegistrarInterface as Registrar;
use TinyBlocks\Contracts\ViewInterface as View;

/**
 * Application
 *
 * @package TinyBlocks
 */
class App implements Application
{
    /**
     * Core configuration files
     *
     * @var array
     */
    public static $configFiles = [
        'providers',
        'views',
    ];

    /**
     * The application instance.
     *
     * @var static \TinyBlocks\App
     */
    public static $instance;


    /**
     * The dependency injection container.
     *
     * @var \DI\Container
     */
    public $container;

    /**
     * Blocks collection
     *
     * @var \Illuminate\Support\Collection
     */
    public $blocks;

    /**
     * Registrar
     *
     * @var \TinyBlocks\Contracts\RegistrarInterface
     */
    public $registrar;

    /**
     * Assets
     *
     * @var \TinyBlocks\Contracts\AssetsInterface
     */
    public $assets;

    /**
     * View
     *
     * @var \TinyBlocks\Contracts\ViewInterface
     */
    public $view;

    /**
     * Class constructor.
     *
     * @param string filepath of override configs
     */
    public function __construct(string $config = null)
    {
        /** Configure and build the container. */
        $this->container = (new ContainerBuilder)
            ->addDefinitions($this->config($config))
            ->build();

        /** Set config in container instance */
        $this->container->set('config', $this->config);

        /** Register WordPress hooks. */
        $this->registerHooks();
    }

    /**
     * Singleton constructor.
     *
     * @param  string filepath of override configs
     * @return \TinyBlocks\App
     */
    public static function getInstance(string $config = null) : App
    {
        if (! self::$instance) {
            self::$instance = new App($config);
        }

        return self::$instance;
    }

    /**
     * Initialize instance.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->initializeBlocks();
    }

    /**
     * Initialize blocks as an empty collection.
     *
     * @return void
     */
    public function initializeBlocks() : void
    {
        $this->blocks = Collection::make();
    }

    /**
     * Get container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function container() : Container
    {        
        return $this->container;
    }

    /**
     * Register hooks
     *
     * @return void
     */
    public function registerHooks() : void
    {
        add_action('init', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->makeRegistrar();

                $this->container->set('blocks', $this->blocks);

                $this->registrar()->register($this->blocks);
            }
        });

        add_action('enqueue_block_editor_assets', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->makeAssets();

                $this->assets()->enqueueEditorAssets($this->blocks);
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->makeViews();

                $this->assets()->enqueuePublicAssets($this->blocks);
            }
        });
    }

    /**
     * Make a block instance
     *
     * @return \TinyBlocks\Contracts\BlockInterface
     */
    public function make() : Block
    {
        return $this->container()->make('block');
    }

    /**
     * Instantiate registrar
     *
     * @return \TinyBlocks\Contracts\RegistrarInterface
     */
    public function makeRegistrar() : Registrar
    {
        $this->registrar = $this->container()->make('registrar');

        return $this->registrar;
    }

    /**
     * Instantiate assets manager
     *
     * @return \TinyBlocks\Contracts\AssetsInterface
     */
    public function makeAssets() : Assets
    {
        return $this->assets = $this->container()->make('assets');
    }

    /**
     * Instantiate view provider
     *
     * @return \Illuminate\Support\Collection
     */
    public function makeViews() : Collection
    {
        $viewInstances = Collection::make();

        Collection::make($this->container->get('views'))
            ->each(function ($viewConfig, $view) use ($viewInstances) {
                $view = $this->container()->make('view');

                $viewInstances->push($view, $view->config($viewConfig));
            });

        return $this->viewInstances = $viewInstances;
    }

    /**
     * Add a block instance
     *
     * @param  \TinyBlocks\Contracts\BlockInterface
     * @return \Illuminate\Support\Collection
     */
    public function addBlock($block) : Collection
    {
        $block = is_string($block)
            ? new $block($this->container())
            : $block;

        return $this->blocks()->put($block->name, $block);
    }

    /**
     * Get a block instance
     *
     * @param  string block name
     * @return \TinyBlocks\Contracts\BlockInterface
     */
    public function block(string $blockName) : Block
    {
        return $this->blocks()->get($blockName);
    }

    /**
     * Get the collection of blocks
     *
     * @return \Illuminate\Support\Collection
     */
    public function blocks() : Collection
    {
        return $this->blocks;
    }

    /**
     * Get view provider
     *
     * @return \TinyBlocks\Contracts\View;
     */
    public function view(string $viewKey) : View
    {
        return $this->viewInstances->get($viewKey);
    }

    /**
     * Get registrar
     *
     * @return \TinyBlocks\Contracts\RegistrarInterface;
     */
    public function registrar() : Registrar
    {
        return $this->registrar;
    }

    /**
     * Get assets manager
     *
     * @return \TinyBlocks\Contracts\AssetsInterface;
     */
    public function assets() : Assets
    {
        return $this->assets;
    }

    /**
     * Get config
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Writes configuration to container.
     *
     * @param  array filepath of override configs
     * @return array
     */
    public function config($configOverride = null) : array
    {
        /** 
         * Create configuration from defaults, user
         * supplied values, or a mixture of the two 
         * (should the user not supply all requried configs)
         */
        $this->config = ! $configOverride
            ? Collection::make(self::$configFiles)
                ->mapWithKeys(function ($file) {
                    return $this->requireCoreConfigFile($file);
                })

            : (function() {
                Collection::make(glob("{$configOverride}/*.php"))
                    ->mapWithKeys(function ($file) {
                        return require $file;
                    });

                Collection::make(self::$configFiles)
                    ->each(function ($file) {
                        if (! $config->get($file)) {
                            $config->put($file, $this->requireCoreConfigFile($file));
                        }
                    });
            })();

        /** Return config as array */
        return $this->config->toArray();
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
