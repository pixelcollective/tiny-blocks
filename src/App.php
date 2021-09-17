<?php

namespace TinyBlocks;

use Illuminate\Support\Collection;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use TinyBlocks\Contracts\ApplicationInterface;
use TinyBlocks\Contracts\AssetsInterface;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\RegistrarInterface;
use TinyBlocks\Contracts\ViewInterface;

use function \add_action;

/**
 * Application
 *
 * @package TinyBlocks
 */
class App implements ApplicationInterface
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
     * @var ApplicationInterface
     */
    public static $instance;

    /**
     * The dependency injection container.
     *
     * @var ContainerInterface
     */
    public ContainerInterface $container;

    /**
     * Blocks collection
     *
     * @var Collection
     */
    public Collection $blocks;

    /**
     * Registrar
     *
     * @var RegistrarInterface
     */
    public RegistrarInterface $registrar;

    /**
     * Assets
     *
     * @var AssetsInterface
     */
    public AssetsInterface $assets;

    /**
     * View
     *
     * @var ViewInterface
     */
    public ViewInterface $view;

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
     * @return ApplicationInterface
     */
    public static function getInstance(string $config = null): ApplicationInterface
    {
        if (!self::$instance) {
            self::$instance = new App($config);
        }

        return self::$instance;
    }

    /**
     * Initialize instance.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->initializeBlocks();
    }

    /**
     * Initialize blocks as an empty collection.
     *
     * @return void
     */
    public function initializeBlocks(): void
    {
        $this->blocks = Collection::make();
    }

    /**
     * Get container.
     *
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Register hooks
     *
     * @uses \add_action
     *
     * @return void
     */
    public function registerHooks(): void
    {
        add_action('init', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->registrar = $this->makeRegistrar();

                $this->container->set('blocks', $this->blocks);

                $this->registrar->register($this->blocks);
            }
        });

        add_action('enqueue_block_editor_assets', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->assets = $this->makeAssets();

                $this->assets->enqueueEditorAssets($this->blocks);
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->blocks->isNotEmpty()) {
                $this->assets = $this->makeAssets();

                $this->assets->enqueuePublicAssets($this->blocks);
            }
        });
    }

    /**
     * Make a block instance
     *
     * @return BlockInterface
     */
    public function make(): BlockInterface
    {
        return $this->container->make('block');
    }

    /**
     * Instantiate registrar
     *
     * @return RegistrarInterface
     */
    public function makeRegistrar(): RegistrarInterface
    {
        return $this->registrar = $this->container->make('registrar');
    }

    /**
     * Instantiate assets manager
     *
     * @return AssetsInterface
     */
    public function makeAssets(): AssetsInterface
    {
        return $this->assets = $this->container->make('assets');
    }

    /**
     * Add a block instance
     *
     * @param  BlockInterface
     * @return Collection
     */
    public function addBlock($block): Collection
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
     * @return BlockInterface
     */
    public function block(string $blockName): Block
    {
        return $this->blocks()->get($blockName);
    }

    /**
     * Get the collection of blocks
     *
     * @return Collection
     */
    public function blocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * Get view provider
     *
     * @return ViewInterface
     */
    public function view(string $viewKey): ViewInterface
    {
        return $this->viewInstances->get($viewKey);
    }

    /**
     * Get registrar
     *
     * @return RegistrarInterface
     */
    public function registrar(): RegistrarInterface
    {
        return $this->registrar;
    }

    /**
     * Get assets manager
     *
     * @return AssetsInterface
     */
    public function assets(): AssetsInterface
    {
        return $this->assets;
    }

    /**
     * Get config
     *
     * @return Collection
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Adds configuration to container.
     *
     * @param  array filepath of override configs
     * @return array
     */
    public function config($override = null): array
    {
        $this->config = Collection::make();

        if (!$override) {
            $this->config = Collection::make(self::$configFiles)
                ->mapWithKeys(function ($file) {
                    return $this->requireCoreConfigFile($file);
                });
        } else {
            Collection::make(glob("{$override}/*.php"))
                ->mapWithKeys(function ($file) {
                    return require $file;
                });

            Collection::make(self::$configFiles)->each(function ($file) {
                if (!$this->config->get($file)) {
                    $this->config->put($file, $this->requireCoreConfigFile($file));
                }
            });
        }

        return $this->config->toArray();
    }

    /**
     * Require core configuration file
     *
     * @param  string file
     * @return array
     */
    public function requireCoreConfigFile(string $file): array
    {
        return require realpath(__DIR__ . "/../config/{$file}.php");
    }
}
