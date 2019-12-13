<?php

namespace TinyBlocks\Contracts;

use Illuminate\Support\Collection;
use TinyBlocks\Contracts\BlockInterface;

/**
 * View interface
 *
 * @package TinyPixel\Modules
 */
interface ViewInterface
{
    public function boot();

    public function render(BlockInterface $block) : void;
}
