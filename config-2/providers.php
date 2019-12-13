<?php

use function DI\factory;

use TinyBlocks\Block;
use TinyBlocks\View;
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
    'providers' => [
        /** @see TinyBlocks\Contracts\ViewInterface */
        'viewww' => function (ContainerInterface $app) {
            return new View($app);
        },

        /** @see TinyBlocks\Contracts\BlockInterface */
        'blockkk' => function (ContainerInterface $app) {
            return new Block($app);
        },
    ],
];
