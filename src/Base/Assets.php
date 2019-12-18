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
     * Class constructor.
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->blocks = Collection::make();
    }

    /**
     * Enqueue editor assets.
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function enqueueEditorAssets(Collection $blocks): void
    {
        $blocks->each(function ($block) {
            $block->editorScripts->each(function ($script) {
                $this->enqueueScript($script);
            });

            $block->editorStyles->each(function ($style) {
                $this->enqueueStyle($style);
            });
        });
    }

    /**
     * Enqueue public assets.
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function enqueuePublicAssets(Collection $blocks): void
    {
        $blocks->each(function ($block) {
            $block->publicScripts->each(function ($script) {
                $this->enqueueScript($script);
            });

            $block->publicStyles->each(function ($style) {
                $this->enqueueStyle($style);
            });
        });
    }

    /**
     * Enqueue block script.
     *
     * @param  array  block
     * @return void
     */
    public function enqueueScript(Asset $script): void
    {
        wp_enqueue_script(
            $script->getName(),
            $script->getUrl(),
            $script->getManifest() ? $script->getManifest()->dependencies : $script->getDependencies(),
            $script->getManifest() ? $script->getManifest()->version      : $script->getVersion(),
        );
    }

    /**
     * Enqueue block style.
     *
     * @param  array  block
     * @return void
     */
    public function enqueueStyle(Asset $style): void
    {
        wp_enqueue_style($style->getName(), $style->getUrl());
    }
}
