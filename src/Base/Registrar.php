<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\RegistrarInterface;

/**
 * Abstract Registrar
 *
 * @package TinyBlocks
 */
abstract class Registrar implements RegistrarInterface
{
    /**
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Register block
     *
     * @return void
     */
    public function register()
    {
        // --
    }
}
