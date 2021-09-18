<?php

namespace TinyBlocks\Base;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use TinyBlocks\Contracts\BlockInterface;
use TinyBlocks\Contracts\ViewInterface;
use TinyBlocks\Contracts\RegistrarInterface;

abstract class Registrar implements RegistrarInterface
{
    /**
     * View instances
     *
     * @var Collection
     */
    public Collection $views;

    /**
     * Class constructor
     *
     * @param ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->views = new Collection();
    }

    /**
     * Initialize blocks
     *
     * @param Collection $config
     */
    public function initialize(): RegistrarInterface
    {
        $this->container->get('blocks')->map(function (BlockInterface | string $block) {
            $block = is_string($block)
                ? new $block($this->container)
                : $block;

            $key = $block->getViewKey() ?? 'app';

            if (!$view = $this->getViewInstance($key)) {
                $this->setViewInstance($key, $this->makeViewInstance($key));
            }

            $block->setView($this->getViewInstance($key));

            return $block;
        });

        return $this;
    }

    /**
     * Get View instance
     *
     * @return View or null if none found
     */
    public function getViewInstance(string $key): ?ViewInterface
    {
        return $this->views->get($key);
    }

    /**
     * Add view instance to view instances collection
     *
     * @param  string
     * @param  View
     * @return void
     */
    public function setViewInstance(string $key, ViewInterface $view): void
    {
        $this->views->put($key, $view);
    }

    /**
     * Make view
     */
    public function makeViewInstance(string $key): ViewInterface {
        return $this->getViewEngine()
            ->register((object) $this->container->get('instances')[$key])
            ->boot();
    }

    /**
     * Make View from container
     *
     * @return object
     */
    public abstract function getViewEngine(): ViewInterface;

    /**
     * Register blocks.
     *
     * Called on init.
     *
     * @return void
     */
    public function register(): RegistrarInterface
    {
        $this->initialize()->container->get('blocks')->each(
            function (Block $block) {;
                $block->getView()->render($block);
            }
        );

        return $this;
    }

    /**
     * Register blocks
     *
     * @param  Collection $blocks
     * @return void
     */
    public function registerWithServer(): void
    {
        $this->blocks->each(function (Block $block) {
            $block->getView()->render($block);
        });
    }
}
