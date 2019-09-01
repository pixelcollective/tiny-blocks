<?php
/**
 * Plugin Name:     Based Blocks
 * Description:     Modular block building
 * Version:         1.0.0
 * Author:          Kelly Mears, Tiny Pixel
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     plugin
 *
 * Requires PHP 7+
 */
namespace Plugin;

require_once __DIR__ . '/vendor/autoload.php';

use TinyPixel\Base\Base;
use TinyPixel\Base\Bootloader;
use Plugin\Providers\BlocksServiceProvider;
use Plugin\Providers\AdminServiceProvider;

(new class {
    public function __invoke()
    {
        \add_action('base\register', function (Base $base) {
            $base->register(BlocksServiceProvider::class);
            $base->register(AdminServiceProvider::class);
        });
    }
})();
