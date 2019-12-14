<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Contracts\BlockInterface;

/**
 * Abstract Block
 *
 * @package TinyBlocks
 */
abstract class Block implements BlockInterface
{
    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * View path
     * @var string
     */
    public $view;

    /**
     * Data
     * @var \Illuminate\Support\Collection
     */
    public $data;

    /**
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Get block name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set block name
     *
     * @param  string name
     * @return void
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Get view
     *
     * @return string path of view
     */
    public function getView() : string
    {
        return $this->view;
    }

    /**
     * Set view
     *
     * @param  string path of view
     * @return void
     */
    public function setView(string $viewPath) : void
    {
        $this->view = $viewPath;
    }

    /**
     * Get data
     *
     * @return string block data
     */
    public function getData() : Collection
    {
        return $this->data;
    }

    /**
     * Set view
     *
     * @param  string block data
     * @return void
     */
    public function setData(Collection $data) : void
    {
        $this->data = $data;
    }
}
