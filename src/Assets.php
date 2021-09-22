<?php

namespace TinyPixel\Blocks;

use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\AssetsInterface;

use function \wp_enqueue_script;
use function \wp_enqueue_style;

class Assets implements AssetsInterface
{
    /**
     * Application container
     *
     * @var Collection
     */
    public $container;

    /**
     * Class constructor.
     *
     * @param Collection
     */
    public function __construct(Collection $container)
    {
        $this->container = $container;
    }

    /**
     * Enqueue editor assets.
     *
     * @param Collection
     * @return void
     */
    public function enqueueEditorAssets(Collection $blocks): AssetsInterface
    {
        $blocks->each(function ($block) {
            $block->editorScripts->each(function ($script) {
                $this->enqueueScript($script);
            });

            $block->editorStyles->each(function ($style) {
                $this->enqueueStyle($style);
            });
        });

        return $this;
    }

    /**
     * Enqueue public assets.
     *
     * @param  Collection
     * @return Assets
     */
    public function enqueuePublicAssets(Collection $blocks): AssetsInterface
    {
        $blocks->each(function ($block) {
            $block->publicScripts->each(function ($script) {
                $this->enqueueScript($script);
            });

            $block->publicStyles->each(function ($style) {
                $this->enqueueStyle($style);
            });
        });

        return $this;
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
            $script->getManifest() ? $script->getManifest()->version : $script->getVersion(),
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
