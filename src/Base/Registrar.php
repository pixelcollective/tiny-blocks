<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\ViewInterface as View;
use TinyBlocks\Contracts\RegistrarInterface;

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
     * @var \Illuminate\Support\Collection
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
     * @param \Illuminate\Support\Collection $config
     */
    public function initializeBlocks()
    {
        $this->blocks->map(function (Block $block) {
            $blockInstance = is_string($block)
                ? new $block($this->container)
                : $block;

            $blockView = $blockInstance->getView();

            if (! $blockViewInstance = $this->getViewInstance($blockView)) {
                $blockViewInstance = $this->makeViewInstance($blockView);

                $this->collectViewInstance($blockView, $blockViewInstance);
            }

            $blockInstance->setViewInstance($blockViewInstance);

            return $blockInstance;
        });
    }

    /**
     * Add view instance to view instances collection
     *
     * @param  string
     * @param  View
     * @return void
     */
    public function collectViewInstance(string $viewKey, View $view) : void
    {
        $this->viewInstances->put($viewKey, $view);
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
    public function register() : void
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
    public function filterBlockData() : void
    {
        add_filter('render_block_data', function (array $block) {
            $block['attrs'] = Collection::make(
                $block['attrs'],
            )->map(function ($attr) {
                return is_array($attr) ? (object) $attr : $attr;
            })->toArray();

            return $block;
        }, 10, 2);
    }

    /**
     * Register blocks
     *
     * @param  Collection $blocks
     * @return void
     */
    public function registerWithServer() : void
    {
        $this->blocks->each(function (Block $block) { 
            register_block_type($block->getName(), [
                'render_callback' => function (array $attr, string $innerContent) use ($block) {
                    $block->setData([
                        'attr'    => $attr,
                        'content' => $innerContent,
                    ]);

                    return $this->block->getViewInstance()->render($block);
                }
            ]);
        });
    }
}
