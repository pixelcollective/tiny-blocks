<?php

namespace TinyPixel\Blocks\Contracts;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use TinyPixel\Blocks\Contracts\BlockInterface;

interface ApplicationInterface
{
    public function main(string $config): void;

    public function getContainer(): ContainerInterface;

    public function collect(string $key): Collection;

    public function getBlock(string $key): BlockInterface;
}
