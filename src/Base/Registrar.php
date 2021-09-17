<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\ViewInterface as View;
use TinyBlocks\Contracts\RegistrarInterface;

use function \add_filter;

/**
 * Abstract Registrar
 *
 * @package TinyBlocks
 */
abstract class Registrar implements RegistrarInterface
{
    /**
     * View instances
     *
     * @var Collection
     */
    public $viewInstances;

    /**
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->config = $this->container->get('config');
        $this->views  = $this->config->get('views');

        $this->viewInstances = Collection::make();
    }

    /**
     * Initialize blocks
     *
     * @param Collection $config
     */
    public function initializeBlocks()
    {
        $this->blocks->map(function (Block $block) {
            $blockInstance = is_string($block)
                ? new $block($this->container)
                : $block;

            $blockView = $blockInstance->getView();

            if (!$blockViewInstance = $this->getViewInstance($blockView)) {
                $blockViewInstance = $this->makeViewInstance($blockView);

                $this->collectViewInstance($blockView, $blockViewInstance);
            }

            $blockInstance->setViewInstance($blockViewInstance);

            return $blockInstance;
        });
    }

    /**
     * Get View instance
     *
     * @return View or null if none found
     */
    public function getViewInstance(string $viewKey)
    {
        return $this->viewInstances->get($viewKey);
    }

    /**
     * Add view instance to view instances collection
     *
     * @param  string
     * @param  View
     * @return void
     */
    public function collectViewInstance(string $viewKey, View $view): void
    {
        $this->viewInstances->put($viewKey, $view);
    }

    /**
     * Make View from container
     *
     * @return object
     */
    public function makeViewInstance($viewKey)
    {
        $view = $this->container->make('view');

        $view->register((object) $this->container->get('views')[$viewKey]);

        $view->boot();

        return $view;
    }

    /**
     * Register blocks.
     *
     * Called on init.
     *
     * @return void
     */
    public function register(): void
    {
        /** Blocks aren't ready until init */
        $this->blocks = $this->container->get('blocks');

        /** Associate blocks with view instances */
        $this->initializeBlocks();

        /** Prep block data for templates */
        $this->filterBlockData();

        /** Register blocks and provide render methods to WP */
        $this->registerWithServer();
    }

    /**
     * Filter block data.
     *
     * @return void
     */
    public function filterBlockData(): void
    {
        add_filter('render_block_data', function (array $block) {
            $attributes = Collection::make($block['attrs']);

            $this->blocks->each(function ($blockInstance) use (&$attributes) {
                if ($className = $blockInstance->getClassName()) {
                    $attributes->put('className', $className);
                }
            });

            $block['attrs'] = $attributes->toArray();

            return $block;
        }, 10, 2);
    }

    /**
     * Register blocks
     *
     * @param  Collection $blocks
     * @return void
     */
    public function registerWithServer(): void
    {
        $this->blocks->each(function (Block $block) {
            $view = $block->getViewInstance();

            $view->doRenderCallback($block);
        });
    }
}
