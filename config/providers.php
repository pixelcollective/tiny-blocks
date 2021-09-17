<?php

use TinyBlocks\App;
use TinyBlocks\Block;
use TinyBlocks\View;
use TinyBlocks\Asset;
use TinyBlocks\Assets;
use TinyBlocks\Registrar;
use Psr\Container\ContainerInterface;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | The following definitions are used by the core classes when
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
    'view' => function (ContainerInterface $app) {
        return new View($app);
    },

    /** @see TinyBlocks\Contracts\BlockInterface */
    'block' => function (ContainerInterface $app) {
        return new Block($app);
    },

    /** @see TinyBlocks\Contracts\AssetsInterface */
    'assets' => function (ContainerInterface $app) {
        return new Assets($app);
    },

    /** @see TinyBlocks\Contracts\AssetInterface */
    'asset' => function (ContainerInterface $app) {
        return new Asset($app);
    },

    /** @see TinyBlocks\Contracts\RegistrarInterface */
    'registrar' => function (ContainerInterface $app) {
        return new Registrar($app);
    },
];
