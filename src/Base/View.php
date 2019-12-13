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
     * @param  Psr\Container\ContainerInterface container instance
     * @return void
     */
    public function register(Container $app) : void
    {
        $this->blade = new Blade(
            ...$this->config()
        );
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
}
