<?php

namespace TinyBlocks\Provider;

use eftec\bladeone\BladeOne as Blade;
use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Contracts\BlockInterface;

/**
 * View
 *
 * @package TinyBlocks
 */
class View implements ViewInterface
{
    /** @var eftec\bladeone\BladeOne */
    protected $blade;

    /** @var string */
    protected $baseDir;

    /** @var string */
    protected $cacheDir;

    /** @var int */
    protected $debug;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // --
    }

    /**
     * Boot view implementation
     *
     * @return void
     */
    public function boot() : void
    {
        $this->blade = new Blade(...$this->config());
    }

    /**
     * Return BladeOne configuration as an array
     *
     * @return array bladeone configuration
     */
    public function getConfig() : array
    {
        return [$this->baseDir, $this->cacheDir, $this->debug];
    }

    /**
     * Return BladeOne base directory
     *
     * @return string
     */
    public function getBaseDir() : string
    {
        return $this->baseDir;
    }

    /**
     * Return BladeOne cache directory
     *
     * @return string
     */
    public function getCacheDir() : string
    {
        return $this->cacheDir;
    }

    /**
     * Return BladeOne debug mode
     *
     * @return int debug constant
     */
    public function getDebug() : int
    {
        return $this->debug;
    }

    /**
     * Render a view
     *
     * @return string rendered view
     */
    public function render(BlockInterface $block) : string
    {
        return $this->blade->run($block->getView(), $block->getData());
    }
}
