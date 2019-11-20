<?php
/**
 * Plugin Name:     Block Modules
 * Description:     Backbone for modular block building
 * Version:         0.1.0
 * Author:          Kelly Mears, Tiny Pixel
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     tinyblocks
 */
namespace TinyPixel\Modules;

require_once __DIR__ . '/vendor/autoload.php';

use \WP_Error;
use TinyPixel\Modules\Runtime;

(new class {
    /**
     * Class invocation.
     *
     * @param  string default base directory (WP_PLUGINS)
     * @return void
     */
    public function __invoke(string $baseDir) : void
    {
        $this->plugin = new Runtime($baseDir);
    }
})(realpath(__DIR__ . '/..'));
