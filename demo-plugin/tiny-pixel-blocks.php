<?php
/**
 * Plugin Name: Tiny Pixel Blocks
 * Description: WordPress editor content extensions
 */

add_filter('blockmodules', function ($registrar) {
    $registrar->addPlugin('tiny-pixel-blocks', [
        'dir' => 'tiny-pixel-blocks',
    ]);

    $registrar->addBlock('tiny-pixel-blocks', [
        'dir' => 'block',
        'handle' => 'tiny-pixel/block',
        'filepaths' => [
            'views' => 'resources/views',
            'dist'  => 'dist',
        ],
        'editor' => [
            'js'  => 'scripts/editor',
            'css' => 'styles/editor',
        ],
        'public' => [
            'css' => 'styles/public',
        ],
        'view' => 'block.blade.php',
    ]);
});
