<?php

namespace TinyBlocks;

use DI\ContainerBuilder;
use DI\Container;
use Illuminate\Support\Collection;
use TinyBlocks\Contracts\ApplicationInterface;
use TinyBlocks\Contracts\AssetsInterface;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\RegistrarInterface;
use TinyBlocks\Contracts\ViewInterface;

use function \add_action;

class App implements ApplicationInterface
{
    /**
     * Configuration files
     */
    public static $configFiles = [
        'providers',
        'views',
    ];

    /**
     * The application instance
     */
    public static $instance;

    /**
     * The DI container
     */
    public Container $container;

    /**
     * Blocks collection
     */
    public Collection $blocks;

    /**
     * Registrar
     */
    public RegistrarInterface $registrar;

    /**
     * Assets
     */
    public AssetsInterface $assets;

    /**
     * View
     */
    public ViewInterface $view;

    /**
     * Class constructor
     *
     * @param string filepath of override configs
     */
    public function __construct(string $config = null)
    {
        $this->container = (new ContainerBuilder)
            ->addDefinitions($this->config($config))
            ->build();

        $this->container
            ->set('config', $this->config);

        $this->container
            ->get('blocks')->map(function ($block) {
                $this->addBlock($block);
            });

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
        $this->blocks = Collection::make();
    }

    /**
     * Get container.
     *
     * @return Container
     */
    public function getContainer(): Container
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
                $this->container->set('blocks', $this->blocks);
                $this->registrar = $this->makeRegistrar();
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
        return $this->container->make(Block::class);
    }

    /**
     * Instantiate registrar
     *
     * @return RegistrarInterface
     */
    public function makeRegistrar(): RegistrarInterface
    {
        return $this->registrar = $this->container->make(Registrar::class);
    }

    /**
     * Instantiate assets manager
     *
     * @return AssetsInterface
     */
    public function makeAssets(): AssetsInterface
    {
        return $this->assets = $this->container->make(Assets::class);
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
            ? new $block($this->getContainer())
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
    public function view(string $key): ViewInterface
    {
        return $this->view->container->get($key);
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
    public function config($userConfig = null): array
    {
        $this->config = Collection::make();

        if (!$userConfig) {
            $this->config = Collection::make(self::$configFiles)
                ->mapWithKeys(function ($file) {
                    return $this->requireCoreConfigFile($file);
                });
        } else {
            Collection::make(glob("{$userConfig}/*.php"))
                ->mapWithKeys(function ($file) {
                    return require $file;
                });

            Collection::make(self::$configFiles)->each(function ($file) {
                if (!$this->config->get($file)) {
                    $this->config->put($file, $this->requireCoreConfigFile($file));
                }
            });
        }

        $this->container->set('config', $this->config->toArray());
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
