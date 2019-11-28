<?php

namespace TinyPixel\Modules;

use function \add_action;
use function \register_block_type;

use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;

/**
 * Registration
 *
 * @since   0.0.1
 * @version 0.3.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Registration
{
    /** @var string baseDir */
    public static $baseDir;

    /** @var \Illuminate\Support\Collection Collected plugins */
    public static $plugins;

    /** @var \Illuminate\Support\Collection Collected blocks */
    public static $blocks;

    /** @var eftec\bladeone\BladeOne View Engine */
    public static $viewEngine;

    /**
     * Constructor
     *
     * @param eftec\bladeone\BladeOne       view engine
     * @param Illuminate\Support\Collection blocks
     * @param string                        base directory
     */
    public function __construct(
        string $baseDir,
        \eftec\bladeone\BladeOne $blade,
        \Illuminate\Support\Collection $blocks,
        \Illuminate\Support\Collection $plugins
    ) {
        self::$baseDir    = $baseDir;
        self::$viewEngine = $blade;
        self::$blocks     = $blocks;
        self::$plugins    = $plugins;

        return $this;
    }

    /**
     * Register block views
     *
     * @return void
     */
    public function registerViews() : void
    {
        self::$blocks->each(function ($block, $plugin) {
            $this->registerView($block, $plugin);
        });
    }

    /**
     * Register block view
     *
     * @param  array $blockName
     * @return void
     */
    public function registerView(\Illuminate\Support\Collection $block, string $plugin) : void
    {
        register_block_type($block->get('handle'), [
            'render_callback' => function ($attr, $content) use ($block, $plugin) {
                $view = [$this->view($plugin, $block), [
                    'attr'    => (object) $attr,
                    'content' => $content,
                ]];

                return self::$viewEngine->run(...$view);
            }
        ]);
    }

    /**
     * Returns view
     *
     * @param  array $block
     * @return string
     */
    public function view(string $plugin, \Illuminate\Support\Collection $block) : string
    {
        return sprintf(
            "%s/%s%s/%s",
            self::$plugins[$plugin]['dir'],
            $block->get('dir') ? "{$block->get('dir')}/" : null,
            $block->get('filepaths')['views'] ?: 'resources/views',
            $block->get('view') ?: 'block',
        );
    }
}
