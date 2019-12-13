<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use TinyBlocks\Contracts\AssetsInterface;

/**
 * Abstract Assets
 *
 * @package TinyBlocks
 */
abstract class Assets implements AssetsInterface
{
    public function enqueueEditorAssets()
    {
        // --
    }

    public function enqueuePublicAssets()
    {
        // --
    }
}
