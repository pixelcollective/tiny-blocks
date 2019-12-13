<?php
/**
 * Plugin Name:     TinyBlocks
 * Description:     Slim backbone for modular block building
 * Version:         0.3.0
 * Author:          Tiny Pixel Collective
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     block-modules
 */
namespace TinyBlocks;

require_once __DIR__ . '/vendor/autoload.php';

use \WP_Error;
use TinyBlocks\Application;

(new class {
    /**
     * Class invocation.
     *
     * @param  string default base directory (WP_PLUGINS)
     * @return void
     */
    public function __invoke() : void
    {
        Application::getInstance();
    }
})();
