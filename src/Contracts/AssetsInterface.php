<?php

namespace TinyBlocks\Contracts;

use Illuminate\Support\Collection;

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
