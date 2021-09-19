<?php

namespace TinyPixel\Blocks\Abstract;

use eftec\bladeone\BladeOne;
use TinyPixel\Blocks\Support\Fluent;

use Psr\Container\ContainerInterface;
use TinyPixel\Blocks\Contracts\BlockInterface;
use TinyPixel\Blocks\Contracts\ViewInterface;

use function \register_block_type;

abstract class View implements ViewInterface
{
    /**
     * View engine
     */
    protected BladeOne $blade;

    /**
     * Base directory
     */
    protected string $baseDir;

    /**
     * Cache directory
     */
    protected string $cacheDir;

    /**
     * Debug mode enabled
     */
    protected bool $debug;

    /**
     * Class constructor
     *
     * @param ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Register view implementation
     *
     * @param object config
     */
    public function register(object $config): ViewInterface
    {
        $this->setBaseDir($config->dir);
        $this->setCacheDir($config->cache);
        $this->setDebug($config->debug);

        return $this;
    }

    /**
     * Boot view implementation
     *
     * @param  ContainerInterface container instance
     * @return void
     */
    public function boot(): ViewInterface
    {
        $this->blade = new BladeOne(
            ...$this->getConfig()->toArray()
        );

        return $this;
    }

    /**
     * Render a view.
     *
     * @param  BlockInterface block instance
     * @return void
     */
    public function render(BlockInterface $block): void
    {
        register_block_type($block->getName(), [
            'render_callback' => function ($attr, $innerContent) use ($block) {
                return $this->blade->run(
                    $block->getTemplate(),
                    $block->with([
                        'attr'    => new Fluent($attr),
                        'content' => $innerContent,
                    ])
                );
            }
        ]);
    }

    /**
     * Get view configuration as an array.
     *
     * @return array bladeone configuration
     */
    public function getConfig(): Fluent
    {
        return new Fluent([
            $this->getBaseDir(),
            $this->getCacheDir(),
            $this->getDebug()
        ]);
    }

    /**
     * Get view base directory.
     *
     * @return string
     */
    public function getBaseDir(): string
    {
        return $this->baseDir;
    }

    /**
     * Set view base directory.
     *
     * @param  string
     * @return void
     */
    public function setBaseDir(string $baseDir): void
    {
        $this->baseDir = $baseDir;
    }

    /**
     * Get view cache directory.
     *
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * Set view cache directory.
     *
     * @param  string
     * @return void
     */
    public function setCacheDir(string $cacheDir): void
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Get view debug mode.
     *
     * @return int debug constant
     */
    public function getDebug(): int
    {
        return $this->debug;
    }

    /**
     * Set view debug mode.
     *
     * @param  int debug constant
     * @return void
     */
    public function setDebug(int $debug): void
    {
        $this->debug = $debug;
    }
}
