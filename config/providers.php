<?php

use function DI\factory;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\App;
use TinyBlocks\Block;
use TinyBlocks\View;
use TinyBlocks\Assets;
use TinyBlocks\Registrar;

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
    /** @see TinyBlocks\Contracts\ApplicationInterface */
    'application' => function () {
        return App::getInstance();
    },

    /** @see TinyBlocks\Contracts\ViewInterface */
    'view' => function (Container $app) {
        return new View($app);
    },

    /** @see TinyBlocks\Contracts\BlockInterface */
    'block' => function (Container $app) {
        return new Block($app);
    },

    /** @see TinyBlocks\Contracts\AssetsInterface */
    'assets' => function (Container $app) {
        return new Assets($app);
    },

    /** @see TinyBlocks\Contracts\RegistrarInterface */
    'registrar' => function (Container $app) {
        return new Registrar($app);
    },
];
