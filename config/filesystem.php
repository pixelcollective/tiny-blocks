<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filesystems
    |--------------------------------------------------------------------------
    |
    | This configuration provides references to the path and URL values of
    | important disk locations.
    |
    */
    'disk' => [
        'wordpress' => [
            'dir'   => WP_CONTENT_DIR,
            'url'   => \content_url(),
        ],
    ],
];
