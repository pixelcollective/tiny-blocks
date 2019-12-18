<?php

$cachePath = wp_upload_dir()['basedir'] . '/blocks';

return [
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

    'views' => [
        'wordpress' => [
            'dir'   => WP_CONTENT_DIR,
            'cache' => $cachePath,
            'debug' => 0,
        ],

        'plugins' => [
            'dir' => WP_PLUGIN_DIR,
            'cache' => $cachePath,
            'debug' => 0,
        ],

        'tinyblocks' => [
            'dir'   => WP_CONTENT_DIR . '/plugins/tinyblocks/demo/resources/views',
            'cache' => $cachePath,
            'debug' => 0,
        ]
    ],
];
