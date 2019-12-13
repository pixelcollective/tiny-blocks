<?php

namespace TinyBlocks;

use Illuminate\Support\Collection;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ApplicationInterface as Application;
use TinyBlocks\Contracts\BlockInterface as Block;

/**
 * Blocks Application
 *
 * @package TinyBlocks
 */
class Blocks implements Application
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
     * The application instance.
     *
     * @static \TinyBlocks\Application
     */
    public static $instance;

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
     * @return \TinyBlocks\Application
     */
    public static function getInstance(string $config = null) : Application
    {
        if (! self::$instance) {
            self::$instance = new Blocks($config);
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

        $this->view()->register($this->container());
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
     * Register hooks
     */
    public function registerHooks()
    {
        add_action('init', function () {
            $this->registerBlocks();
        });

        add_action('enqueue_block_editor_assets', function () {
            $this->enqueueEditorAssets();
        });

        add_action('wp_enqueue_scripts', function () {
            $this->enqueuePublicAssets();
        });
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
        $this->makeView();
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
     * Register a block instance
     *
     * @param  \TinyBlocks\Contracts\BlockInterface
     * @return void
     */
    public function register(Block $block) : void
    {
        $this->blocks()->put($block->name, $block);
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
     * Instantiate view provider
     *
     * @return void
     */
    public function makeView()
    {
        $this->view = $this->container()->make('view', [
            'container' => $this->container(),
        ]);
    }

    /**
     * Get view provider
     *
     * @return \TinyBlocks\Contracts\View;
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * Instantiate registrar
     *
     * @return void
     */
    public function makeRegistrar()
    {
        $this->registrar = $this->container()->make('registrar', [
            'container' => $this->container(),
        ]);
    }

    /**
     * Get registrar
     *
     * @return \TinyBlocks\Contracts\RegistrarInterface;
     */
    public function registrar()
    {
        return $this->registrar;
    }

    /**
     * Instantiate assets manager
     *
     * @return void
     */
    public function makeAssets()
    {
        $this->assets = $this->container()->make('assets', [
            'container' => $this->container(),
        ]);
    }

    /**
     * Get assets manager
     *
     * @return \TinyBlocks\Contracts\AssetsInterface;
     */
    public function assets()
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
     * Register blocks
     *
     * @return void
     */
    public function registerBlocks() : void
    {
        if ($this->blocks->isNotEmpty())
            $this->registrar()->register($this->blocks);
    }

    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueueEditorAssets()
    {
        if ($this->blocks->isNotEmpty())
            $this->Assets()->enqueueEditor($this->blocks);
    }

    /**
     * Enqueue public assets
     *
     * @return void
     */
    public function enqueuePublicAssets()
    {
        if ($this->blocks->isNotEmpty())
            $this->Assets()->enqueuePublic($this->blocks);
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
