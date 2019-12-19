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
    public $template = 'block';

    /** classnames */
    public $className = 'wp-block-demo';

    /**
     * Data to be passed as variables to the view.
     */
    public function with(array $data): array
    {
        return $data;
    }

    /**
     * Setup assets
     */
    public function setupAssets(): void
    {
        $editorStyle = $this->makeAsset()
            ->setName('tinyblocks/demo/css')
            ->setUrl(plugins_url() . '/tinyblocks/demo/dist/styles/editor.css');

        $editorScript = $this->makeAsset()
            ->setName('tinyblocks/demo/js')
            ->setUrl(plugins_url() . '/tinyblocks/demo/dist/scripts/editor.js')
            ->setManifest(WP_PLUGIN_DIR . '/tinyblocks/demo/dist/scripts/editor.asset.php');

        $this->addEditorStyle($editorStyle);
        $this->addEditorScript($editorScript);
    }
}
