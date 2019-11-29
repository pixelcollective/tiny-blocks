<?php
/**
 * Plugin Name:     Block Modules
 * Description:     Backbone for modular block building
 * Version:         0.4.0
 * Author:          Tiny Pixel Collective
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     block-modules
 */
namespace TinyPixel\Modules;

require_once __DIR__ . '/vendor/autoload.php';

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
        Runtime::getInstance($baseDir);
    }
})(WP_PLUGIN_DIR);
