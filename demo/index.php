<?php

/** 
 * Plugin Name:        Demo Plugin
 * Plugin URI:         https://foo.bar/
 * Description:        Developer tooling for Gutenberg
 * Version:            1.0.0
 * Author:             Foo & Bar
 * Author URI:         https://foo.man
 * GitHub Plugin URI:  https://github.com/foo/bar
 * Primary Branch:     main
 *
 * License:            MIT License
 * License URI:        https://opensource.org/licenses/MIT
 */

namespace Foo;

require __DIR__.'/vendor/autoload.php';

add_action('tinypixel/blocks/loaded', function ($app) {
    $configPath = realpath(__DIR__ . '/config');
    $app->main($configPath);
});
