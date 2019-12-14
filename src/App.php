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
        'filesystem',
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
     * View instances
     *
     * @var \Illumiante\Support\Collection
     */
    public $viewInstances;

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
     * Assets
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
        $container = (new ContainerBuilder)
                ->addDefinitions($this->config($config))
                ->build();

        $this->setContainer($container);
    }

    /**
     * Get singleton instance
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
     * Initialize
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->initializeBlocks();

        $this->makeProviders();

        $this->registerHooks();
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
     * Get container
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function container() : Container
    {
        return $this->container;
    }

    /**
     * Set container
     *
     * @param  \Psr\Container\ContainerInterface
     * @return void
     */
    public function setContainer(Container $container) : void
    {
        $this->container = $container;
    }

    /**
     * Make providers
     *
     * @return void
     */
    public function makeProviders() : void
    {
        $this->makeRegistrar();

        $this->makeAssets();

        $this->makeViews();
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
                $this->registrar()->register($this->blocks);
            }
        });

        add_action('enqueue_block_editor_assets', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->assets()->enqueueEditorAssets($this->blocks);
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->blocks->isNotEmpty()) {
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
        return $this->registrar = $this->container()->make('registrar');
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
     * Register a block instance
     *
     * @param  \TinyBlocks\Contracts\BlockInterface
     * @return \Illuminate\Support\Collection
     */
    public function register(Block $block) : Collection
    {
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
     * Boot view provider
     *
     * @return void
     */
    public function bootViewProvider()
    {
        $this->view->boot();
    }

    /**
     * Get configuration
     *
     * @param  array filepath of override configs
     * @return array
     */
    public function config($configOverride = null) : array
    {
        $config = ! $configOverride
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
                    ->each(function ($file) use ($config) {
                        if (! $config->get($file)) {
                            $config->put($file, $this->requireCoreConfigFile($file));
                        }
                    });
            })();

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
