<?php
namespace TinyPixel\Modules;

use \add_action;
use \register_block_type;

use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;

/**
 * Block Modules
 *
 * @since   1.2.0
 * @version 1.2.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Modules
{
    /** @var string baseDir */
    public static $baseDir;

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
        \eftec\bladeone\BladeOne $blade,
        \Illuminate\Support\Collection $blocks,
        string $baseDir
    ) {
        self::$viewEngine = $blade;
        self::$blocks     = $blocks;
        self::$baseDir    = $baseDir;
    }

    /**
     * Register block views
     *
     * @return void
     */
    public function registerViews() : void
    {
        self::$blocks->each(function ($block) {
            $this->registerView($block);
        });
    }

    /**
     * Register block view
     *
     * @param  array $blockName
     * @return void
     */
    public function registerView(\Illuminate\Support\Collection $block) : void
    {
        register_block_type($block->get('handle'), [
            'render_callback' => function ($attr, $content) use ($block) {
                $view = [$this->view($block), [
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
    public function view(\Illuminate\Support\Collection $block) : string
    {
        return sprintf(
            "%s/%s/%s",
            $block->get('plugin') ?: basename($block->get('dir')),
            $block->get('views')  ?: 'src/blade',
            $block->get('view')   ?: 'render.blade.php'
        );
    }

    /**
     * Supply user data for blade directives
     *
     * @param  \WP_User $user
     * @return void
     */
    public function setUser(\WP_User $user) : void
    {
        (!$user->ID===0) && self::$viewEngine->setAuth(
            $user->data->user_nicename,
            $user->roles[0]
        );
    }
}
