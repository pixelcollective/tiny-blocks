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

(new class
{
    public function __invoke()
    {
        $tinyblocks = \TinyBlocks\App::getInstance();
        $tinyblocks->initialize();

        /** functionally create and define a new block */
        $myBlock = $tinyblocks->make();

        $myBlock->setName('tinyblock/example');
        $myBlock->setView('plugins');
        $myBlock->setTemplate('tinyblocks/resources/views/block.blade.php');
        /** functionally define a script */
        $script = $myBlock->makeAsset()
            ->setName('tinyblocks/example/js')
            ->setUrl(WP_PLUGIN_DIR . '/tinyblocks/dist/editor.js')
            ->setManifest(plugins_url() . '/tinyblocks/dist/editor.manifest.php');

        $myBlock->addEditorScript($script);

        /** finalize */
        $tinyblocks->addBlock($myBlock);

        /** pre-define a block */
        $tinyblocks->addBlock(\TinyBlocks\Demo\DemoBlock::class);
    }
})();
