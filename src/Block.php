<?php

namespace TinyBlocks;

use TinyBlocks\Base\Block as Base;
use TinyBlocks\Asset;

class Block extends Base
{
    /**
     * Style asset factory
     *
     * @return Asset
     */
    public function makeAsset(): Asset {
        return $this->container->get(Asset::class);
    }
}
