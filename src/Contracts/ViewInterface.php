<?php

namespace TinyBlocks\Contracts;

use TinyBlocks\Contracts\BlockInterface;

interface ViewInterface
{
    public function register(object $config): ViewInterface;

    public function boot(): ViewInterface;

    public function render(BlockInterface $block): void;
}
