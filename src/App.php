<?php

namespace TinyPixel\Blocks;

use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\ApplicationInterface;
use TinyPixel\Blocks\Support\Fluent;

use function \add_action;

class App implements ApplicationInterface
{
    /**
     * The application instance
     */
    public static ApplicationInterface $instance;

    /**
     * The DI container
     */
    public Collection $container;

    /**
     * Constructor
     */
    public function __construct() {
        // ✨
    }

    /**
     * Singleton constructor.
     *
     * @param  string filepath of override configs
     * @return ApplicationInterface
     */
    public static function getInstance(): ApplicationInterface
    {
        if (!self::$instance) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    /**
     * Main
     *
     * @return void
     */
    public function main(string $config): void
    {
        $this->makeContainer($config);
        $this->registerHooks();
    }

    /**
     *
     */
    public function makeContainer(string $configDir): void
    {
        $this->container = Collection::make(
            require_once $configDir . "/app.php"
        );

        /**
         * Instantiate factory providers
         */
        $this->collect('providers')->each(
            function ($factory, $name) {
                $this->container->put($name, $factory($this->container));
            }
        );

        /**
         * Instantiate views
         */
        $this->container->put('views',
            $this->collect('views')->map(function (array $properties) {
                return $this->make('view')
                    ->boot(new Fluent($properties));
            })->toArray()
        );

        /**
         * Instantiate blocks
         */
        $this->container->put('blocks',
            $this->collect('blocks')->map(function (string $block) {
                $instance = new $block($this->container);

                if ($instance->viewKey) {
                    $instance->setView(
                        $this->get('views')[$instance->viewKey]
                    );
                }

                return $instance;
            })
        );
    }

    /**
     * Register hooks
     *
     * @uses \add_action
     *
     * @return void
     */
    public function registerHooks(): void
    {
        add_action('init', function () {
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->get('registrar')->register($this->container);
            }
        });

        add_action('enqueue_block_editor_assets', function () {
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->get('assets')
                    ->enqueueEditorAssets($this->collect('blocks'));
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->collect('blocks')->isNotEmpty()) {
                $this->get('assets')
                    ->enqueuePublicAssets($this->collect('blocks'));
            }
        });

        $this->get('registrar')->register();
    }

    /**
     * Get the collection of blocks
     *
     * @return Collection
     */
    public function collect(string $key): Collection
    {
        return Collection::make($this->container->get($key));
    }

    /**
     * Liberate a boi from the container
     */
    public function get(string $key)
    {
        return $this->container->get($key);
    }

    /**
     * Get a service from the container
     *
     * @param  string $key
     * @return mixed
     */
    public function make(string $name)
    {
        $Service = $this->container->get('providers')[$name];

        return $Service($this->container);
    }
}
