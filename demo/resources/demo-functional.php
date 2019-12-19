<?php

require_once __DIR__ . '/vendor/autoload.php';

/** initialize tinyblocks */
$tinyblocks = \TinyBlocks\App::getInstance();
$tinyblocks->initialize();

/** create and define a new block */
$myBlock = $tinyblocks->make();

$myBlock->setName('tinyblock/example');
$myBlock->setView('plugins');
$myBlock->setTemplate('tinyblocks/resources/views/block.blade.php');

/** define a script */
$script = $myBlock->makeAsset()
  ->setName('tinyblocks/example/js')
  ->setUrl(WP_PLUGIN_DIR . '/tinyblocks/dist/editor.js')
  ->setManifest(plugins_url() . '/tinyblocks/dist/editor.manifest.php');

/**  */
$myBlock->addEditorScript($script);
