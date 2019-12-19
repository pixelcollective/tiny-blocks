<?php

/**
 * Plugin name: TinyBlocks
 * Plugin description: A block framework for professional WordPress developers.
 */

namespace TinyBlocks;

require __DIR__ . '/vendor/autoload.php';

use TinyBlocks\App;

$tinyblocks = App::getInstance();
$tinyblocks->initialize();
