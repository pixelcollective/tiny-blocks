<?php

use function DI\factory;

use TinyBlocks\Block;
use TinyBlocks\View;
use TinyBlocks\AssetsManager;
use TinyBlocks\Registrar;
use Psr\Container\ContainerInterface;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | The following class definitions are used by the core classes when
    | registering blocks and rendering views. Here you may register your own
    | class definitions or substitute in alternative implmentations of
    | core services (for example: using Illuminate\View instead of eftect\BladeOne)
    |
    */
    /** @see TinyBlocks\Contracts\ViewInterface */
    'view' => function (ContainerInterface $app) {
        return new View($app);
    },

    /** @see TinyBlocks\Contracts\BlockInterface */
    'block' => function (ContainerInterface $app) {
        return new Block($app);
    },

    /** @see TinyBlocks\Contracts\AssetsManagerInterface */
    'assets' => function (ContainerInterface $app) {
        return new AssetsManager($app);
    },

    /** @see TinyBlocks\Contracts\RegistrarInterface */
    'registrar' => function (Container $app) {
        return new Registrar($app);
    },
];
