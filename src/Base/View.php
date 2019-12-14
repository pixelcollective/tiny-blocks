<?php

namespace TinyBlocks\Base;

use eftec\bladeone\BladeOne as Blade;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\ViewInterface;

/**
 * Abstract View
 *
 * @package TinyBlocks
 */
abstract class View implements ViewInterface
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
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Configure view instance
     *
     * @param  array $config
     * @return void
     */
    public function config(array $config) : void
    {
        $this->config = $config;
    }

    /**
     * Boot view implementation
     *
     * @param  Psr\Container\ContainerInterface container instance
     * @return void
     */
    public function register() : void
    {
        /* $this->blade = new Blade(
            ...$this->getConfig()
        ); */
    }

    /**
     * Render a view
     *
     * @param  \TinyBlocks\Contracts\BlockInterface block instance
     * @return string rendered view
     */
    public function render(Block $block) : string
    {
        return $this->blade->run(
            $block->getView(),
            $block->getData()
        );
    }

    /**
     * Return BladeOne configuration as an array
     *
     * @return array bladeone configuration
     */
    public function getConfig() : array
    {
        return [
            $this->getBaseDir(),
            $this->getCacheDir(),
            $this->getDebug()
        ];
    }

    /**
     * Return BladeOne base directory
     *
     * @return string
     */
    public function getBaseDir() : string
    {
        return $this->baseDir ?: '';
    }

    /**
     * Return BladeOne cache directory
     *
     * @return string
     */
    public function getCacheDir() : string
    {
        return $this->cacheDir ?: '';
    }

    /**
     * Return BladeOne debug mode
     *
     * @return int debug constant
     */
    public function getDebug() : int
    {
        return $this->debug ?: 0;
    }
}
