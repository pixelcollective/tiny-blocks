<?php

namespace TinyPixel\Blocks\Contracts;

use TinyPixel\Blocks\Contracts\BlockInterface;

interface ViewInterface
{
    public function boot(Object $config): ViewInterface;

    public function render(BlockInterface $block): void;
}
