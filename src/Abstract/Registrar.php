<?php

namespace TinyPixel\Blocks\Abstract;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use TinyPixel\Blocks\Contracts\BlockInterface;
use TinyPixel\Blocks\Contracts\ViewInterface;
use TinyPixel\Blocks\View;
use TinyPixel\Blocks\Contracts\RegistrarInterface;

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
        $this->blocks = Collection::make($this->container->get('blocks'));
        $this->views =  Collection::make($this->container->get('views'));
    }

    /**
     * Initialize blocks
     *
     * @param Collection $config
     */
    public function initialize(): RegistrarInterface
    {
        $this->blocks->map(
            function (BlockInterface | string $block) {
                $block = is_string($block)
                    ? new $block($this->container)
                    : $block;

                $key = $block->getViewKey();

                if (!$this->viewInstanceIsRegistered($key)) {
                    $this->setViewInstance($key, $this->makeViewInstance($key));
                }

                $block->setView($this->getViewInstance($key));

                return $block;
            }
        );

        return $this;
    }

    /**
     * Get View instance
     *
     * @return View or null if none found
     */
    public function getViewInstance(string $key): array
    {
        return $this->container->get('view.instances')[$key];
    }

    /**
     * Get View instance
     *
     * @return View or null if none found
     */
    public function viewInstanceIsRegistered(string $key): bool
    {
        return array_key_exists($key, $this->container->get('view.instances'));
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
        $this->container->add("view.instances", $key,  $view);
    }

    /**
     * Make view
     */
    public function makeViewInstance(string $key): ViewInterface {
        return $this->container->make(View::class)
            ->register((object) $this->container->get('views')[$key])
            ->boot();
    }

    /**
     * Register blocks.
     *
     * Called on init.
     *
     * @return void
     */
    public function register(): RegistrarInterface
    {
        $this->initialize()->blocks->each(
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
