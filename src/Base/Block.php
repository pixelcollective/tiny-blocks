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
     * @var string
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
     * Editor scripts
     * @var \Illuminate\Support\Collection
     */
    public $editorScripts;

    /**
     * Editor styles
     * @var \Illuminate\Support\Collection
     */
    public $editorStyles;

    /**
     * Public scripts
     * @var \Illuminate\Support\Collection
     */

    /**
     * Class constructor.
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->editorScripts = Collection::make();
        $this->editorStyles  = Collection::make();
        $this->publicScripts = Collection::make();
        $this->publicStyles  = Collection::make();

        $this->using();
    }

    /**
     * Using
     *
     * @return void
     */
    public function using()
    {
        // --
    }

    /**
     * Get block name.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set block name.
     *
     * @param  string name
     * @return void
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Get view.
     *
     * @return string path of view
     */
    public function getView() : string
    {
        return $this->view;
    }

    /**
     * Set view.
     *
     * @param  string path of view
     * @return void
     */
    public function setView(string $view) : void
    {
        $this->view = $view;
    }

    /**
     * Set view instance
     *
     * @param  View
     * @return void
     */
    public function setViewInstance(View $viewInstance) : void
    {
        $this->viewInstance = $viewInstance;
    }

    /**
     * Get view instance
     * 
     * @return View
     */
    public function getViewInstance() : View
    {
        return $this->viewInstance;
    }

    /**
     * Get blade template.
     * 
     * @return string
     */
    public function getTemplate() : string
    {
        return $this->template;
    }

    /**
     * Set template
     *
     * @param  string $template
     * @return void
     */
    public function setTemplate(string $template) : void
    {
        $this->template = $template;
    }

    /**
     * Get data
     *
     * @return string block data
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set view
     *
     * @param  string block data
     * @return void
     */
    public function setData(array $data) : void
    {
        $this->data = $data;
    }

    /**
     * Style asset factory
     *
     * @return Asset
     */
    public function makeAsset() : Asset
    {
        return $this->container->make('asset');
    }

    /**
     * Get editor scripts
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getEditorScripts() : Collection
    {
        return $this->editorScripts;
    }

    /**
     * Add editor script
     * 
     * @param  \TinyBlocks\Contracts\AssetInterface
     * @return void
     */
    public function addEditorScript(Asset $editorScript) : void
    {
        $this->editorScripts->put($editorScript->getName(), $editorScript);
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
    public function getEditorStyles() : Collection
    {
        return $this->editorStyles;
    }

    /**
     * Add editor styles
     * 
     * @param  \TinyBlocks\Contracts\AssetInterface
     * @return void
     */
    public function addEditorStyle(Asset $editorStyle) : void
    {
        $this->editorStyles->put($editorStyle->getName(), $editorStyle);
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
}
