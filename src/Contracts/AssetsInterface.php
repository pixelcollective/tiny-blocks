<?php

namespace TinyBlocks\Contracts;

use DI\ContainerBuilder;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\BlockInterface as Block;

/**
 * Assets Interface
 *
 * @package TinyBlocks
 * @subpackage Contracts
 */
interface AssetsInterface
{
    public function enqueueEditorAssets(Collection $blocks);

    public function enqueuePublicAssets(Collection $blocks);
}
