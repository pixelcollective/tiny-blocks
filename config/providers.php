<?php

use Psr\Container\ContainerInterface as Container;

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
return [
    App::class => function () {
        return TinyBlocks\App::getInstance();
    },

    View::class => function (Container $app) {
        return new TinyBlocks\View($app);
    },

    Block::class => function (Container $app) {
        return new TinyBlocks\Block($app);
    },

    Assets::class => function (Container $app) {
        return new TinyBlocks\Assets($app);
    },

    Asset::class => function (Container $app) {
        return new TinyBlocks\Asset($app);
    },

    Registrar::class => function (Container $app) {
        return new TinyBlocks\Registrar($app);
    },
];
