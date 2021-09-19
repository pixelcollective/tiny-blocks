<?php

/**
 * Plugin Name:        Tiny Blocks
 * Plugin URI:         https://roots.io/plugins/tiny-blocks/
 * Description:        Developer tooling for Gutenberg
 * Version:            1.0.0
 * Author:             Tiny Pixel Collective, LLC
 * Author URI:         https://tinypixel.dev
 * GitHub Plugin URI:  https://github.com/pixelcollective/tiny-blocks
 * Primary Branch:     main
 *
 * License:            MIT License
 * License URI:        https://opensource.org/licenses/MIT
 */

namespace TinyPixel;

add_action('plugins_loaded', function () {
    if (!class_exists(Blocks\App::class)) {
        require_once file_exists($autoloader = __DIR__ . '/vendor/autoload.php')
            ? $autoloader
            : __DIR__ . '/bootstrap/autoload.php';
    }

    do_action('tinypixel/blocks/loaded');
});
