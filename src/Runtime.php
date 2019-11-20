<?php
namespace TinyPixel\Modules;

use function \add_action;

use eftec\bladeone\BladeOne as Blade;
use Illuminate\Support\Collection;
use TinyPixel\Modules\Modules;

/**
 * Block modules runtime
 *
 * @since   1.2.0
 * @version 1.2.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Runtime
{
    /** @var string base directory */
    public static $baseDir;

    /**
     * Class constructor
     *
     * @param string base directory
     */
    public function __construct(string $baseDir)
    {
        self::$baseDir = $baseDir;

        $this->blocks  = Collection::make();

        add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorAssets']);
        add_action('wp_enqueue_scripts',          [$this, 'enqueuePublicAssets']);
        add_action('init',                        [$this, 'registryInit']);
    }

    /**
     * Initialize registry
     *
     * @param  string baseDir
     * @return void
     */
    public function registryInit() : void
    {
        $this->config = Collection::make([
            'base'  => $this->filter('base_path_blockmodules', self::$baseDir),
            'cache' => $this->filter('cache_path_blockmodules', WP_CONTENT_DIR . '/uploads/block-modules'),
            'mode'  => $this->filter('debug_blockmodules', Blade::MODE_AUTO),
        ]);

        $this->filter('register_blockmodules', Collection::make())->each(function ($block) {
            $this->blocks->push(Collection::make($block));
        }) && $this->blocks->unique();

        $this->blade = $this->setViewEngine(
            $this->config->get('base'),
            $this->config->get('cache'),
            $this->config->get('mode')
        );

        $this->registry = $this->setBlockRegistry(
            $this->blade,
            $this->blocks,
            $this->config->get('base')
        );

        $this->registerBlocks();
    }

    /**
     * Register blocks.
     *
     * @return void
     */
    public function registerBlocks() : void
    {
        // Enable @user, @guest in views
        if (! $this->filter('disable_user_blockmodules', false)) {
            $this->registry->setUser($user = \wp_get_current_user());
        }

        $this->registry->registerViews();
    }

    /**
     * Manufacture View Engine objects
     *
     * @param  string base directory
     * @param  string cache location
     * @param  int    view engine debug mode
     * @return \eftec\bladeone\BladeOne
     */
    public function setViewEngine(
        string $base,
        string $cache,
        int    $mode
    ) : \eftec\bladeone\BladeOne {
        return new Blade($base, $cache, $mode);
    }

    /**
     * Manufacture Registry objects
     *
     * @param  \eftec\bladeone\BladeOne       view engine
     * @param  \Illuminate\Support\Collection blocks
     * @param  string                         base directory
     * @return \TinyPixel\Modules\Modules     blocks registry
     */
    public function setBlockRegistry(
        \eftec\bladeone\BladeOne       $viewEngine,
        \Illuminate\Support\Collection $blocks,
        string                         $base
    ) : \TinyPixel\Modules\Modules {
        return new Modules($viewEngine, $blocks, $base);
    }

    /**
     * Return filter result or default
     *
     * @param  string $filter
     * @param  mixed  $default
     * @return mixed
     */
    public function filter(string $filter, $default)
    {
        if (!has_filter($filter)) {
            return $default;
        }

        return apply_filters($filter, $default);
    }

    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueueEditorAssets() : void
    {
        $this->enqueueBlockScripts($this->blocks, 'editor');
        $this->enqueueBlockStyles($this->blocks,  'editor');
    }

    /**
     * Enqueue public assets
     *
     * @return void
     */
    public function enqueuePublicAssets() : void
    {
        $this->enqueueBlockScripts($this->blocks, 'public');
        $this->enqueueBlockStyles($this->blocks,  'public');
    }

    /**
     * Enqueue block scripts at a specified location (public, editor)
     *
     * @param  \Illuminate\Support\Collection of blocks
     * @param  string                         where to enqueue
     * @return void
     */
    public function enqueueBlockScripts(
        \Illuminate\Support\Collection $blocks,
        string $location
    ) : void {
        $blocks->each(function ($block) use ($location) {
            ($script = $this->getAsset($block, $location, 'js')) ? $this->enqueueScript([
                $this->getName($block, $script),
                $this->getUrl($block, $script, 'js'),
                $this->getManifest($block, $script)->dependencies,
                $this->getManifest($block, $script)->version,
            ]): null;
        });
    }

    /**
     * Enqueue block styles at a specified location (public, editor)
     *
     * @param  \Illuminate\Support\Collection of blocks
     * @param  string                         where to enqueue
     * @return void
     */
    public function enqueueBlockStyles(
        \Illuminate\Support\Collection $blocks,
        string $location
    ) : void {
        $blocks->each(function ($block) use ($location) {
            ($style = $this->getAsset($block, $location, 'css')) ? $this->enqueueStyle([
                $this->getName($block, $style),
                $this->getUrl($block, $style, 'css'),
                $this->getManifest($block, $style)->dependencies,
                $this->getManifest($block, $style)->version,
                'all',
            ]): null;
        });
    }

    /**
     * Enqueue block script.
     *
     * @param  array  block
     * @param  string asset
     * @return void
     */
    public function enqueueScript(array $enqueueParams) : void
    {
        wp_enqueue_script(...$enqueueParams);
    }

    /**
     * Enqueue block style.
     *
     * @param  array  block
     * @param  string asset
     * @return void
     */
    public function enqueueStyle(array $enqueueParams) : void
    {
        wp_enqueue_style(...$enqueueParams);
    }

    /**
     * Resolve manifest file path from registered block
     *
     * @param  \Illuminate\Support\Collection  block
     * @param  string manifest file basename
     * @return object manifest contents
     */
    protected function getManifest(
        \Illuminate\Support\Collection $block,
        string $manifest
    ) : object {
        if (file_exists($file = sprintf(
            "%s/%s/%s.asset.php",
            $block->get('dir'),
            $this->getDist($block),
            $manifest
        ))) {
            return (object) include $file;
        }

        return (object) ['dependencies' => [], 'version' => null];
    }

    /**
     * Registered block name
     *
     * @param  \Illuminate\Support\Collection block
     * @param  string                         asset
     * @return string
     */
    protected function getName(
        \Illuminate\Support\Collection $block,
        string $asset
    ) : string {
        return "{$block->get('handle')}/{$asset}";
    }

    /**
     * getUrl
     *
     * @uses \plugins_url
     *
     * @param \Illuminate\Support\Collection block
     * @param string                         asset
     * @param string                         filetype
     */
    protected function getUrl(
        \Illuminate\Support\Collection $block,
        string $asset,
        string $filetype
    ) : string {
        return \plugins_url(sprintf(
            "%s/%s/%s.%s",
            $this->getPlugin($block),
            $this->getDist($block),
            $asset,
            $filetype
        ));
    }

    /**
     * Get plugin name
     *
     * @param  Illuminate\Support\Collection  block
     * @return string                         plugin name
     */
    protected function getPlugin(\Illuminate\Support\Collection $block) : string
    {
        return $block->get('plugin') ?: basename($block->get('dir'));
    }

    /**
     * Get plugin script
     *
     * @param  \Illuminate\Support\Collection  block
     * @param  string                          location
     * @return string                          plugin script
     */
    protected function getAsset(
        \Illuminate\Support\Collection $block,
        string $location,
        string $type
    ) {
        $enqueueLocation = Collection::make($block->get($location));

        return $enqueueLocation->isNotEmpty() ? $enqueueLocation->get($type) : null;
    }

    /**
     * Get dist
     *
     * @param  \Illuminate\Support\Collection  block data
     * @param  string location (public, editor)
     * @return string style basename
     */
    protected function getDist(
        \Illuminate\Support\Collection $block
    ) : string {
        return $block->get('dist') ?: 'dist';
    }
}
