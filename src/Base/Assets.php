<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\AssetsInterface;

/**
 * Abstract Assets
 *
 * @package TinyBlocks
 */
abstract class Assets implements AssetsInterface
{
    /**
     * Application container
     * @var \Psr\Container\ContainerInterface
     */
    public $container;

    /**
     * Blocks collection
     * @var \Illuminate\Support\Collection
     */
    public $blocks;

    /**
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->blocks = Collection::make();
    }

    /**
     * Enqueue editor assets
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function enqueueEditorAssets(Collection $blocks) : void
    {
        $this->enqueueScripts($blocks, 'editor');
        $this->enqueueStyles($blocks, 'editor');
    }

    /**
     * Enqueue public assets
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function enqueuePublicAssets(Collection $blocks) : void
    {
        $this->enqueueScripts($blocks, 'public');
        $this->enqueueStyles($blocks, 'public');
    }

    /**
     * Enqueue block editor scripts
     *
     * @param  \Illuminate\Support\Collection
     * @param  string where to enqueue
     * @return void
     */
    public function enqueueScripts(Collection $blocks, string $location) : void {
        $blocks->each(function ($block) use ($location) {
            if (! isset($block->$location->script) ||
                ! $block->$location->script) {
                return;
            }

            $this->enqueueScript([
                $block->$location->script->name,
                $block->$location->script->url,
                $block->$location->script->dependencies,
                $block->$location->script->version,
            ]);
        });
    }

    /**
     * Enqueue block styles
     *
     * @param  \Illuminate\Support\Collection
     * @param  string where to enqueue
     * @return void
     */
    public function enqueueStyles(Collection $blocks, string $location) : void {
        $blocks->each(function ($block) use ($location) {
            if (! isset($block->$location->style) ||
                ! $block->$location->style) {
                return;
            }

            $this->enqueueStyle([
                $block->$location->style->name,
                $block->$location->style->url,
            ]);
        });
    }

    /**
     * Enqueue block script.
     *
     * @param  array  block
     * @return void
     */
    public function enqueueScript(array $enqueueParams) : void
    {
        wp_enqueue_script(...$enqueueParams);
    }

    /**
     * Enqueue block style.
     *
     * @param  array  block
     * @return void
     */
    public function enqueueStyle(array $enqueueParams) : void
    {
        wp_enqueue_style(...$enqueueParams);
    }
}
