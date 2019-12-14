<?php
namespace TinyBlocks\Contracts;

use DI\ContainerBuilder;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ApplicationInterface as Application;
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
    public function container() : Container;

    public function make() : Block;

    public function block(string $blockName) : Block;

    public function blocks() : Collection;

    public function makeRegistrar() : Registrar;

    public function registrar() : Registrar;

    public function makeAssets() : Assets;

    public function assets() : Assets;

    public function makeViews() : Collection;

    public function view(string $viewKey) : View;

    public function register(Block $block) : Collection;

    public function bootViewProvider();

    public function config($configOverride = null) : array;

    public function requireCoreConfigFile(string $file) : array;
}
