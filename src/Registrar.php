<?php

namespace TinyBlocks;

use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Base\Registrar as Base;
use TinyBlocks\View;

class Registrar extends Base
{
    /**
     * Make View from container
     *
     * @return object
     */
    public function getViewEngine(): ViewInterface
    {
        return $this->container->make(View::class);
    }
}
