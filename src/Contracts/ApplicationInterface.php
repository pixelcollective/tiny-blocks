<?php

namespace TinyBlocks\Contracts;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use TinyBlocks\Contracts\AssetsInterface as Assets;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\RegistrarInterface as Registrar;
use TinyBlocks\Contracts\ViewInterface as View;

interface ApplicationInterface
{
    public function getContainer(): ContainerInterface;

    public function make(): BlockInterface;

    public function block(string $blockName): BlockInterface;

    public function blocks(): Collection;

    public function makeRegistrar(): Registrar;

    public function registrar(): Registrar;

    public function makeAssets(): Assets;

    public function assets(): Assets;

    public function view(string $viewKey): View;

    public function addBlock($block): Collection;

    public function config($configOverride = null): array;

    public function requireCoreConfigFile(string $file): array;
}
