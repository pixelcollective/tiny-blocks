<?php

namespace TinyBlocks;

use Illuminate\Support\Collection;
use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Contracts\BlockInterface;

/**
 * Block interface
 *
 * @package TinyBlocks
 */
class Block implements BlockInterface
{
    /** @var string */
    public $name;

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
    public function getData() : \Illuminate\Support\Collection
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
