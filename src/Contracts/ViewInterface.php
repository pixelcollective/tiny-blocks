<?php

namespace TinyBlocks\Contracts;

use TinyBlocks\Contracts\BlockInterface as Block;

/**
 * View interface
 *
 * @package TinyPixel\Modules
 */
interface ViewInterface
{
    public function register(object $config): void;

    public function boot();

    public function doRenderCallback(Block $block): void;
}
