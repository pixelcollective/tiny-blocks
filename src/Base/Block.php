<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ViewInterface as View;
use TinyBlocks\Contracts\BlockInterface;

/**
 * Abstract Block
 *
 * @package TinyBlocks
 */
abstract class Block implements BlockInterface
{
    /**
     * Block name.
     * @var string
     */
    public $name;

    /**
     * View engine.
     * @var string
     */
    public $view;

    /**
     * View instance.
     * @var \TinyBlocks\Contracts\ViewInterface
     */
    public $viewInstance;

    /**
     * Template file
     * @var string
     */
    public $template;

    /**
     * Data
     * @var array
     */
    public $data;

    /**
     * Classname.
     * @var string
     */
    public $className;

    /**
     * Asset types
     */
    public $assetTypes = [
        'editorScripts',
        'editorStyles',
        'publicScripts',
        'publicStyles',
    ];

    /**
     * Class constructor.
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->initializeAssetCollections();

        $this->setupAssets();
    }

    /**
     * Initialize asset collections
     *
     * @return void
     */
    public function initializeAssetCollections(): void
    {
        Collection::make($this->assetTypes)->each(function ($asset) {
            $this->$asset = Collection::make();
        });
    }

    /**
     * Space to enqueue assets, etc.
     *
     * @return void
     */
    public function setupAssets(): void
    {
        return;
    }

    /**
     * Data to be passed to block view template.
     *
     * @param  array
     * @return array
     */
    public function with(array $data): array
    {
        return $data;
    }

    /**
     * Get block name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set block name.
     *
     * @param  string name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get view.
     *
     * @return string path of view
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Set view.
     *
     * @param  string path of view
     * @return void
     */
    public function setView(string $view): void
    {
        $this->view = $view;
    }

    /**
     * Set view instance
     *
     * @param  View
     * @return void
     */
    public function setViewInstance(View $viewInstance): void
    {
        $this->viewInstance = $viewInstance;
    }

    /**
     * Get view instance
     *
     * @return View
     */
    public function getViewInstance(): View
    {
        return $this->viewInstance;
    }

    /**
     * Get blade template.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set template
     *
     * @param  string $template
     * @return \TinyPixel\Contracts\BlockInterface
     */
    public function setTemplate(string $template): BlockInterface
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get data
     *
     * @return string block data
     */
    public function getData()
    {
        return $this->data ?: [];
    }

    /**
     * Set view
     *
     * @param  string block data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Style asset factory
     *
     * @return Asset
     */
    public function makeAsset(): Asset
    {
        return $this->container->make('asset');
    }

    /**
     * Get editor scripts
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEditorScripts(): Collection
    {
        return $this->editorScripts;
    }

    /**
     * Add editor script
     *
     * @param  \TinyBlocks\Contracts\AssetInterface
     * @return void
     */
    public function addEditorScript(Asset $editorScript): void
    {
        $this->editorScripts->put(
            $editorScript->getName(),
            $editorScript
        );
    }

    /**
     * Set editor scripts
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function setEditorScripts(Collection $editorScripts)
    {
        $this->editorScripts = $editorScripts;
    }

    /**
     * Get editor styles
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEditorStyles(): Collection
    {
        return $this->editorStyles;
    }

    /**
     * Add editor styles
     *
     * @param  \TinyBlocks\Contracts\AssetInterface
     * @return void
     */
    public function addEditorStyle(Asset $editorStyle): void
    {
        $this->editorStyles->put($editorStyle->getName(), $editorStyle);
    }

    /**
     * Add public styles
     *
     * @param  \TinyBlocks\Contracts\AssetInterface
     * @return void
     */
    public function addPublicStyle(Asset $publicStyle): void
    {
        $this->publicStyles->put($publicStyle->getName(), $publicStyle);
    }

    /**
     * Set editor styles
     *
     * @param  \Illuminate\Support\Collection
     * @return void
     */
    public function setEditorStyles(Collection $editorStyles)
    {
        $this->editorStyles = $editorStyles;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className ?: '';
    }

    /**
     * Set classname
     *
     * @param  string
     * @return string
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }
}
