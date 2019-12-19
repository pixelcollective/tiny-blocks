<?php

namespace TinyBlocks\Base;

use eftec\bladeone\BladeOne as Blade;
use Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\BlockInterface as Block;
use TinyBlocks\Contracts\ViewInterface;

/**
 * Abstract View
 *
 * @package TinyBlocks
 */
abstract class View implements ViewInterface
{
    /** @var eftec\bladeone\BladeOne */
    protected $blade;

    /** @var string */
    protected $baseDir;

    /** @var string */
    protected $cacheDir;

    /** @var int */
    protected $debug;

    /**
     * Class constructor
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Register view implementation
     */
    public function register(object $config): void
    {
        $this->setBaseDir($config->dir);

        $this->setCacheDir($config->cache);

        $this->setDebug($config->debug);
    }

    /**
     * Boot view implementation
     *
     * @param  Psr\Container\ContainerInterface container instance
     * @return void
     */
    public function boot(): void
    {
        $this->blade = new Blade(
            ...$this->getConfig()
        );
    }

    /**
     * Render a view.
     *
     * @param  \TinyBlocks\Contracts\BlockInterface block instance
     * @return void
     */
    public function doRenderCallback(Block $block): void
    {
        register_block_type($block->getName(), [
            'render_callback' => function ($attr, $innerContent) use ($block) {
                $block->setData($block->with([
                    'attr'    => (object) $attr,
                    'content' => $innerContent,
                ]));

                return $this->blade->run(
                    $block->getTemplate(),
                    $block->getData()
                );
            }
        ]);
    }

    /**
     * Get view configuration as a spreadable array.
     *
     * @return array bladeone configuration
     */
    public function getConfig(): array
    {
        return [
            $this->getBaseDir(),
            $this->getCacheDir(),
            $this->getDebug()
        ];
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
