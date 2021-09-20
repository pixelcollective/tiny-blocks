<?php

namespace TinyPixel\Blocks\Contracts;

use TinyPixel\Blocks\Contracts\BlockInterface;

interface ViewInterface
{
    public function boot(Object $properties): ViewInterface;

    public function render(BlockInterface $block): void;
}
