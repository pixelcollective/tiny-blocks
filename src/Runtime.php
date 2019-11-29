<?php
namespace TinyPixel\Modules;

use function \add_action;

use eftec\bladeone\BladeOne as Blade;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use TinyPixel\Modules\Modules;
use TinyPixel\Modules\Registrar;
use TinyPixel\Modules\Runtime;

/**
 * Block modules runtime
 *
 * @since   0.0.1
 * @version 0.3.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Runtime
{
    /** @var string base directory */
    public $baseDir;

    /** @var \TinyPixel\Modules\Runtime self instance */
    public static $instance;

    /**
     * Class constructor
     *
     * @param string base directory
     */
    protected function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;

        $this->blocks  = Collection::make();

        add_action('init', function () {
            $this->config();
            $this->blade = $this->useBladeOne();
            $this->registryInit();
            $this->registerModules();
            $this->registerDirectives();
        });

        add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorAssets']);
        add_action('wp_enqueue_scripts',          [$this, 'enqueuePublicAssets']);
    }

    /**
     * Class singleton constructor
     *
     * @param  string base directory
     * @return \TinyPixel\Modules\Runtime
     */
    public static function getInstance(string $baseDir = __DIR__) : Runtime
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new Runtime($baseDir);
    }

    /**
     * Configure runtime
     */
    public function config()
    {
        $this->config = [
            $this->baseDir   = $this->filter('blockmodules_base',  $this->baseDir),
            $this->cachePath = $this->filter('blockmodules_cache', wp_upload_dir()['basedir'] . '/block/cache'),
            $this->debug     = $this->filter('blockmodules_debug', Blade::MODE_AUTO),
        ];
    }

    /**
     * Initialize registry
     *
     * @param  string baseDir
     * @return void
     */
    public function registryInit() : void
    {
        $this->registrar = Registrar::getInstance(self::getInstance(), $this->blade, $this->baseDir);
    }

    /**
     * Register blocks
     *
     * @param  string baseDir
     * @return void
     */
    public function registerModules() : void
    {
        $this->filter('blockmodules', $this->registrar);

        $this->modules  = $this->registrar->registerAll();
    }

    public function registerDirectives() : void
    {
        new Directives($this->blade, $this->config);
    }

    /**
     * Manufacture View Engine objects
     *
     * @return \eftec\bladeone\BladeOne
     */
    public function useBladeOne() : Blade {
        return new Blade(...$this->config);
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
        $this->enqueueBlockScripts($this->modules->blocks, 'editor');
        $this->enqueueBlockStyles($this->modules->blocks,  'editor');
    }

    /**
     * Enqueue public assets
     *
     * @return void
     */
    public function enqueuePublicAssets() : void
    {
        $this->enqueueBlockScripts($this->modules->blocks, 'public');
        $this->enqueueBlockStyles($this->modules->blocks,  'public');
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
        $blocks->each(function ($block, $plugin) use ($location) {
            ($script = $this->getAsset($block, $location, 'js')) ? $this->enqueueScript([
                $this->getName($block, $script),
                $this->getUrl($plugin, $block, $script, 'js'),
                $this->getManifest($plugin, $block, $script)->dependencies,
                $this->getManifest($plugin, $block, $script)->version,
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
        $blocks->each(function ($block, $plugin) use ($location) {
            ($style = $this->getAsset($block, $location, 'css')) ? $this->enqueueStyle([
                $this->getName($block, $style),
                $this->getUrl($plugin, $block, $style, 'css'),
                $this->getManifest($plugin, $block, $style)->dependencies,
                $this->getManifest($plugin, $block, $style)->version,
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
        string $plugin,
        \Illuminate\Support\Collection $block,
        string $manifest
    ) : object {
        $file = sprintf(
            "%s/%s/%s/%s.asset.php",
            $this->getPluginPath($plugin),
            $this->getBlockDirname($block),
            $this->getDist($block),
            $manifest,
        );

        if (file_exists($file)) {
            return (object) include $file;
        }

        return (object) [
            'dependencies' => [],
            'version'      => null,
        ];
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
        string $plugin,
        \Illuminate\Support\Collection $block,
        string $asset,
        string $filetype
    ) : string {
        return \plugins_url(sprintf(
            "%s/%s/%s/%s.%s",
            $this->getPluginDirname($plugin),
            $this->getBlockDirname($block),
            $this->getDist($block),
            $asset,
            $filetype
        ));
    }

    /**
     * Get block directory
     *
     * @param  Illuminate\Support\Collection  block
     * @return string                         plugin name
     */
    protected function getBlockDirname(\Illuminate\Support\Collection $block) : string
    {
        return $block->get('dir');
    }

    /**
     * Get plugin base path
     *
     * @param  Illuminate\Support\Collection  block
     * @return string                         block name
     */
    protected function getPluginPath(string $plugin) : string
    {
        return sprintf('%s/%s', $this->baseDir, $this->getPluginDirname($plugin));
    }

    /**
     * Get plugin dirname
     *
     * @param  Illuminate\Support\Collection  plugin
     * @return string                         block name
     */
    protected function getPluginDirname(string $plugin) : string
    {
        return $this->modules->plugins[$plugin]['dir'];
    }

    /**
     * Get block script
     *
     * @param  \Illuminate\Support\Collection  block
     * @param  string                          location
     * @return string                          block script
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
     * Get block dist
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
