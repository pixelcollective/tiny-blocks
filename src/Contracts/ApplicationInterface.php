<?php
namespace TinyBlocks\Contracts;

use DI\ContainerBuilder;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ApplicationInterface as Application;
use TinyBlocks\Contracts\BlockInterface as Block;

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

    public function register(Block $block) : void;

    public function block(string $blockName) : Block;

    public function blocks() : Collection;

    public function bootViewProvider();

    public function config($configOverride = null) : array;

    public function requireCoreConfigFile(string $file) : array;
}
