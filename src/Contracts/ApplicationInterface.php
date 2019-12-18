<?php

namespace TinyBlocks\Contracts;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\AssetsInterface as Assets;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\RegistrarInterface as Registrar;
use TinyBlocks\Contracts\ViewInterface as View;

/**
 * Application interface
 *
 * @package TinyBlocks
 * @subpackage Contracts
 */
interface ApplicationInterface
{
    public function container(): Container;

    public function make(): Block;

    public function block(string $blockName): Block;

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
