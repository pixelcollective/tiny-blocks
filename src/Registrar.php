<?php

namespace TinyPixel\Modules;

use Illuminate\Support\Collection;
use TinyPixel\Modules\Modules;
use \eftec\bladeone\BladeOne;

/**
 * Block registrar
 *
 * @since   0.0.1
 * @version 0.3.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Registrar
{
    /** @var \TinyPixel\Modules\Registrar */
    public static $instance;

    /**
     * Class constructor
     *
     * @return \TinyPixel\Modules\Runtime plugin runtime
     * @param  \eftec\bladeone\BladeOne   view engine
     * @param  string                     base directory
     */
    public function __construct(
        \TinyPixel\Modules\Runtime $runtime,
        \eftec\bladeone\BladeOne   $viewEngine,
        string                     $base
    ) {
        $this->runtime    = $runtime;
        $this->viewEngine = $viewEngine;
        $this->base       = $base;

        $this->blockRegistry  = Collection::make();
        $this->pluginRegistry = Collection::make();

        return $this;
    }

    /**
     * Class singleton constructor
     *
     * @return \TinyPixel\Modules\Runtime plugin runtime
     * @param  \eftec\bladeone\BladeOne   view engine
     * @param  string                     base directory
     * @return \TinyPixel\Modules\Registrar
     */
    public static function getInstance(
        \TinyPixel\Modules\Runtime $runtime,
        \eftec\bladeone\BladeOne   $viewEngine,
        string                     $base
    ) : Registrar
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new Registrar($runtime, $viewEngine, $base);
    }

    /**
     * Add block to registry
     *
     * @param array block
     */
    public function addBlock(string $blockName, array $block)
    {
        $this->blockRegistry->put($blockName, Collection::make($block));
    }

    /**
     * Add plugin to registry
     *
     * @param array plugin
     */
    public function addPlugin(string $pluginName, array $plugin)
    {
        $this->pluginRegistry->put($pluginName, $plugin);
    }

    /**
     * Register blocks
     *
     * @return object
     */
    public function registerAll() : object
    {
        $this->viewRegistry = new Registration(
            $this->base,
            $this->viewEngine,
            $this->blockRegistry,
            $this->pluginRegistry,
        );

        $this->viewRegistry->registerViews();

        return (object) [
            'blocks'  => $this->blockRegistry,
            'plugins' => $this->pluginRegistry->toArray(),
        ];
    }

    /**
     * Enable @user, @guest in views
     *
     * @return void
     */
    public function registerUser() : void
    {
        if (! $this->runtime->filter('disable_user_blockmodules', false)) {
            $this->registerUserDirectives(\wp_get_current_user());
        }
    }

    /**
     * Supply user data for blade directives
     *
     * @param  \WP_User $user
     * @return void
     */
    public function registerUserDirectives(\WP_User $user) : void
    {
        (!$user->ID===0) && self::$viewEngine->setAuth(
            $user->data->user_nicename,
            $user->roles[0]
        );
    }
}
