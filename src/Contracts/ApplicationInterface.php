<?php

namespace TinyPixel\Blocks\Contracts;

use Illuminate\Support\Collection;

interface ApplicationInterface
{
    public function main(string $config): void;

    public function collect(string $key): Collection;
}
