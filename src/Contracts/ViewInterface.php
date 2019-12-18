<?php

namespace TinyBlocks\Contracts;

use \Illuminate\Support\Collection;
use \Psr\Container\ContainerInterface as Container;
use \TinyBlocks\Contracts\BlockInterface as Block;

/**
 * View interface
 *
 * @package TinyPixel\Modules
 */
interface ViewInterface
{
    public function register(object $config) : void;

    public function boot();

    public function render(Block $block) : string;
}
