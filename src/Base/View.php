<?php

namespace TinyBlocks\Base;

use eftec\bladeone\BladeOne;
use Psr\Container\ContainerInterface;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\ViewInterface;

use function \register_block_type;

/**
 * Abstract View
 *
 * @package TinyBlocks
 */
abstract class View implements ViewInterface
{
    /**
     * View engine 
     * 
     * @var BladeOne 
     */
    protected BladeOne $blade;

    /** 
     * Base directory
     * 
     * @var string
     */
    protected string $baseDir;

    /** 
     * Cache directory
     * 
     * @var string 
     */
    protected string $cacheDir;

    /**
     * Debug mode enabled
     * 
     * @var bool
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
    public function register(object $config): void
    {
        $this->setBaseDir($config->dir);
        $this->setCacheDir($config->cache);
        $this->setDebug($config->debug);
    }

    /**
     * Boot view implementation
     *
     * @param  ContainerInterface container instance
     * @return void
     */
    public function boot(): void
    {
        $this->blade = new BladeOne(
            ...$this->getConfig()
        );
    }

    /**
     * Render a view.
     *
     * @param  BlockInterface block instance
     * @return void
     */
    public function doRenderCallback(BlockInterface $block): void
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
