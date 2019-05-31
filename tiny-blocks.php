<?php
/**
 * Plugin Name:     Tiny Blocks
 * Description:     Backbone for modular block building
 * Version:         0.1.0
 * Author:          Kelly Mears, Tiny Pixel
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     tinyblocks
 *
 * Requires PHP 7+
 */

namespace TinyPixel\Blocks;

use \Illuminate\Support\Collection;
use function \Roots\view;

(new class {
    /**
     * Plugin runtime
     */
    public function run()
    {
        $this->addBlocks();

        add_action('after_setup_theme', [$this, 'bootBlade']);
        add_action('init', [$this, 'registerBlocks']);
        add_action('enqueue_block_editor_assets', [
            $this, 'enqueueBlocks'
        ]);
    }

    public function addBlocks()
    {
        $this->blocks = apply_filters(
            'register_tinyblock',
            new Collection(),
        );
    }

    /**
     * Registers blocks
     */
    public function registerBlocks()
    {
        $this->blocks->each(function ($block) {
            $block = (object) $block;
            register_block_type($block->handle, [
                'editor_script' => $block->entry,
                'render_callback' => [$this, 'render'],
            ]);
        });
    }

    /**
     * Enqueues blocks
     */
    public function enqueueBlocks()
    {
        $this->blocks->each(function ($block) {
            $block = (object) $block;
            wp_enqueue_script(
                $block->handle,
                $this->asset($block->plugin, $block->entry),
                $this->dependencies('block'),
                '',
                null,
                true
            );
        });
    }

    /**
     * Renders Template
     */
    public function render($attributes, $content)
    {
        $block = (object) [
            'attr' => (object) $attributes,
            'content' => $content,
        ];

        return ! isset($this->blade)
               ? $this->useRoots($block)
               : $this->useFallback($block);
    }

    /**
     * Boots Blade
     */
    public function bootBlade()
    {
        if (!function_exists('\Roots\view')) {
            $this->blade = \eftec\bladeone\BladeOne(
                $this->src($this->pluginsPath),
                $this->cache($this->name),
                Blade::MODE_AUTO,
            );
        }
    }

    /**
     * Renders with \Roots\view()
     */
    public function useRoots($block)
    {
        return \Roots\view($this->bladePath($block), [
            'attr'    => $block->attr,
            'content' => $block->content
        ]);
    }

    /**
     * Returns dependencies depending on if
     * script is specified as a block or plugin
     */
    private function dependencies($type)
    {
        return $type=='block'
        ? ['wp-editor', 'wp-element', 'wp-blocks']
        : ['wp-editor', 'wp-element', 'wp-plugins', 'wp-dom-ready', 'wp-edit-post'];
    }

    /**
     * Returns plugin asset
     */
    public static function asset($plugin, $file)
    {
        return plugin_dir_url($plugin).'/'.$plugin.'/dist/'.$file;
    }

    /**
     * Returns plugin src directory
     */
    public function src($plugin, $file)
    {
        return plugin_dir_path(__DIR__) . $plugin .'/src/'. $file;
    }

    /**
     * Returns blade path
     */
    public function bladePath($block)
    {
        $templateObj = $this->blocks->where('handle', $block->attr->name);

        return $this->src(
            $templateObj->pluck('plugin')->first(),
            "{$templateObj->pluck('blade')->first()}.blade.php"
        );
    }

    /**
     * Returns blade cache dir
     * (if not using Roots\view)
     */
    public static function cache()
    {
        return wp_upload_dir("tiny_cache")['path'];
    }
})->run();
