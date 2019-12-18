<?php

namespace TinyBlocks\Demo;

use TinyBlocks\Base\Block;

/**
 * Demo Block
 * 
 * @package    TinyBlocks
 * @subpackage Demo
 */
class DemoBlock extends Block
{
    /** block name */
    public $name = 'tinyblocks/demo';

    /** view instance */
    public $view = 'tinyblocks';

    /** template file */
    public $template = 'block.blade.php';

    /**
     * Using assets
     */
    public function using() {
        $this->addEditorStyle($this->makeAsset()
            ->setName('tinyblocks/demo/css')
            ->setUrl(plugins_url() . '/tinyblocks/demo/dist/editor.css')
        );

        $this->addEditorScript($this->makeAsset()
            ->setName('tinyblocks/demo/js')
            ->setUrl(plugins_url() . '/tinyblocks/demo/dist/editor.js')
            ->setManifest(WP_PLUGIN_DIR . '/tinyblocks/demo/dist/editor.manifest.php')
        );
    }
}
