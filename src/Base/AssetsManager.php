<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use TinyBlocks\Contracts\AssetsManagerInterface;
/**
 * Abstract Assets Manager
 *
 * @package TinyBlocks
 */
abstract class AssetsManager implements AssetsManagerInterface
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
