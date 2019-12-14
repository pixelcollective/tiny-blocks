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

require_once __DIR__ . '/vendor/autoload.php';

(new class {
    public function __invoke() {
        /** initialize */
        $tinyblocks = \TinyBlocks\App::getInstance();
        $tinyblocks->initialize();

        /** create a new block and define it functionally */
        $myBlock = $tinyblocks->make();
        $myBlock->name = 'tinyblock/example';
        $tinyblocks->register($myBlock);

        dd($tinyblocks);
    }
})();
