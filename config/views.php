<?php

/*
|--------------------------------------------------------------------------
| View engine instances
|--------------------------------------------------------------------------
|
| Here you may specify one or more locations on disk to register as
| a views directory. When using the standard default eftec\blade
| configuration this yields a separate view engine instance per location
| specified. Other view implementations may work differently.
|
*/
return [
    'instances' => [
        'app' => [
            'dir'   => realpath(__DIR__ . '/../'),
            'cache' => wp_upload_dir()['basedir'] . '/app',
            'debug' => 0,
        ],

        'wp' => [
            'dir'   => WP_CONTENT_DIR,
            'cache' => wp_upload_dir()['basedir'] . '/wp',
            'debug' => 0,
        ],

        'plugins' => [
            'dir' => WP_PLUGIN_DIR,
            'cache' => wp_upload_dir()['basedir'] . '/plugins',
            'debug' => 0,
        ],
    ],
];
