<?php

namespace TinyPixel\Blocks;

use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\ViewInterface;
use TinyPixel\Blocks\Contracts\BlockInterface;
use TinyPixel\Blocks\Contracts\AssetInterface;

class Block implements BlockInterface
{
    /**
     * Block name
     *
     * @var string
     */
    public $name;

    /**
     * Block domain
     *
     * @var string
     */
    public $domain;

    /**
     * View template
     * 
     * @var ViewInterface
     */
    public $view;

    /**
     * View instance key
     *
     * @var string
     */
    public $viewKey = 'app';

    /**
     * Template file
     *
     * @var string
     */
    public $template;

    /**
     * Data
     *
     * @var array
     */
    public $data;

    /**
     * CSS classes
     *
     * @var string
     */
    public $className;

    /**
     * Editor scripts
     */
    public $editorScripts;
    public $editorStyles;
    public $publicScripts;
    public $publicStyles;

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
     * @param Collection
     */
    public function __construct(Collection $container)
    {
        $this->container = $container;

        $this->domain = $this->container->get('project')['domain'];

        $this->setName($this->makeName());

        $this->setClassName(
            'wp-block-' . str_replace('/', '-', $this->withDomain($this->name)));

        if (!isset($this->template)) {
            $this->setTemplate($this->name . '/block.blade.php');
        }

        Collection::make($this->assetTypes)->each(
            function ($asset) {
                $this->{$asset} = Collection::make($this->{$asset});
            }
        );

        $this->build();
    }

    /**
     * Make name
     */
    public function makeName() {
        return strtolower(
            preg_replace('/(?<!^)[A-Z]/', '-$0', $this->class())
        );
    }

    /**
     * Class
     */
    public function class() {
        $classname = get_class($this);
        $parts = explode('\\', $classname);

        return array_pop($parts);
    }

    /**
     * Prepend domain
     */
    public function withDomain(string $input): string {
        return $this->domain . '/' . $input;
    }

    /**
     * Space to enqueue assets, etc.
     *
     * @return void
     */
    public function build(): void
    {
        return;
    }

    /**
     * Make asset instance
     *
     * @return Asset
     */
    public function makeAsset(string $ident): AssetInterface
    {
        $assetObj = $this->container->get('providers')['asset']($this->container);
        $assetObj->setName($this->withDomain($this->name . '/' . $ident));

        return $assetObj;
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
    public function setName(string $name): BlockInterface
    {
        $this->name = $name;

        return $this;
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
    public function setView(ViewInterface $view): BlockInterface
    {
        $this->view = $view;

        return $this;
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
    public function getTemplate()
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
    public function setData(array $data): BlockInterface
    {
        $this->data = $data;

        return $this;
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
    public function addEditorScript(AssetInterface $editorScript): void
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
    public function addEditorStyle(AssetInterface $editorStyle): void
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
    public function setClassName(string $className): BlockInterface
    {
        $this->className = $className;

        return $this;
    }
}
