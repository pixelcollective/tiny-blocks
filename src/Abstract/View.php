<?php

namespace TinyPixel\Blocks\Abstract;

use eftec\bladeone\BladeOne;
use TinyPixel\Blocks\Support\Fluent;
use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\BlockInterface;
use TinyPixel\Blocks\Contracts\ViewInterface;

use function \register_block_type;

abstract class View implements ViewInterface
{
    /**
     * View engine
     */
    public BladeOne $blade;

    /**
     * Base directory
     */
    public string $baseDir;

    /**
     * Cache directory
     */
    public string $cacheDir;

    /**
     * Debug mode enabled
     */
    public bool $debug;

    /**
     * Class constructor
     *
     * @param Collection
     */
    public function __construct(Collection $container)
    {
        $this->container = $container;
    }

    /**
     * Boot view implementation
     *
     * @param  Collection container instance
     * @return void
     */
    public function boot(Object $config): ViewInterface
    {
        $this->setBaseDir($config->dir . '/');
        $this->setCacheDir($config->cache . '/');
        $this->setDebug($config->debug);

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
        register_block_type($block->withDomain($block->getName()), [
            'render_callback' => function ($attr, $innerContent) use ($block) {
                return $this->blade->run(
                    $block->getTemplate(),
                    $block->with([
                        'id' => uniqid(),
                        'classname' => $block->getClassName(),
                        'attr' => $this->transform($attr),
                        'content' => $innerContent,
                    ])
                );
            }
        ]);
    }

    /**
     * Mutate template variables prior to rendering view.
     *
     * @param array $attr
     */
    public function transform($attr) {
        $mutate = function($attr) {
            if(is_string($attr)) {
                return $attr;
            }

            if (is_array($attr)) {
                return (object) $this->transform($attr);
            }

            return $attr;
        };

        return (object) Collection::make($attr)->map(
            $mutate
        )->toArray();
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
