<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\AssetInterface;

/**
 * Abstract Block
 *
 * @package TinyBlocks
 */
abstract class Block implements BlockInterface
{
    /**
     * Block name
     *
     * @var string
     */
    public string $name;

    /**
     * View template
     *
     * @var string
     */
    public ViewInterface $view;

    /**
     * View instance key
     *
     * @var string
     */
    public string $viewKey = 'blocks';

    /**
     * Template file
     *
     * @var string
     */
    public string $template;

    /**
     * Data
     *
     * @var array
     */
    public array $data;

    /**
     * CSS classes
     *
     * @var string
     */
    public string $className;

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
     * @param ContainerInterface
     */
    public function __construct(ContainerInterface $container)
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
        Collection::make($this->assetTypes)->each(
            function ($asset) {
                $this->{$asset} = Collection::make();
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
     * Set view instance
     *
     * @param  ViewInterface
     * @return void
     */
    public function setViewKey(string $key): void
    {
        $this->viewKey = $key;
    }

    /**
     * Get view instance
     *
     * @return string
     */
    public function getViewKey(): string
    {
        return $this->viewKey;
    }


    /**
     * Set view instance
     *
     * @param  ViewInterface
     * @return void
     */
    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    /**
     * Get view instance
     *
     * @return ViewInterface
     */
    public function getView(): ViewInterface
    {
        return $this->view;
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
     * @return BlockInterface
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
     * Get editor scripts
     *
     * @return Collection
     */
    public function getEditorScripts(): Collection
    {
        return $this->editorScripts;
    }

    /**
     * Add editor script
     *
     * @param  AssetInterface
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
     * @param  Collection
     * @return void
     */
    public function setEditorScripts(Collection $editorScripts)
    {
        $this->editorScripts = $editorScripts;
    }

    /**
     * Get editor styles
     *
     * @return Collection
     */
    public function getEditorStyles(): Collection
    {
        return $this->editorStyles;
    }

    /**
     * Add editor styles
     *
     * @param  AssetInterface
     * @return void
     */
    public function addEditorStyle(Asset $editorStyle): void
    {
        $this->editorStyles->put($editorStyle->getName(), $editorStyle);
    }

    /**
     * Add public styles
     *
     * @param  AssetInterface
     * @return void
     */
    public function addPublicStyle(AssetInterface $publicStyle): void
    {
        $this->publicStyles->put($publicStyle->getName(), $publicStyle);
    }

    /**
     * Set editor styles
     *
     * @param  Collection
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
