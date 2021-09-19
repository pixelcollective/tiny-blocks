<?php

namespace TinyPixel\Blocks;

use DI\ContainerBuilder;
use DI\Container;
use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\ApplicationInterface;
use TinyPixel\Blocks\Contracts\BlockInterface;

use function \add_action;

class App implements ApplicationInterface
{
    /**
     * The application instance
     */
    public static ApplicationInterface $instance;

    /**
     * The DI container builder
     */
    public ContainerBuilder $builder;

    /**
     * The DI container
     */
    public Container $container;

    /**
     * Constructor
     */
    public function __construct() {
        $this->builder = new ContainerBuilder();
    }

    /**
     * Singleton constructor.
     *
     * @param  string filepath of override configs
     * @return ApplicationInterface
     */
    public static function getInstance(): ApplicationInterface
    {
        if (!self::$instance) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    /**
     * Main
     *
     * @return void
     */
    public function main(string $config): void
    {
        $this->builder->addDefinitions($config);
        $this->container = $this->builder->build();

        $this->registerHooks();
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
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->container->get(RegistrarInterface::class)
                    ->register($this->container);
            }
        });

        add_action('enqueue_block_editor_assets', function () {
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->container->get(AssetsInterface::class)
                    ->enqueueEditorAssets($this->collect('blocks'));
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->container->get(AssetsInterface::class)
                    ->enqueuePublicAssets($this->collect('blocks'));
            }
        });
    }

    /**
     * Get the collection of blocks
     *
     * @return Collection
     */
    public function collect(string $key): Collection
    {
        return Collection::make($this->container->get($key));
    }

    /**
     * Get a block instance
     *
     * @param  string block name
     * @return BlockInterface
     */
    public function getBlock(string $blockName): Block
    {
        return $this->collect('blocks')->get($blockName);
    }
}
