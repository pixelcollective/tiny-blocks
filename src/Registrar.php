<?php

namespace TinyPixel\Blocks;

use Illuminate\Support\Collection;
use TinyPixel\Blocks\Contracts\RegistrarInterface;

class Registrar implements RegistrarInterface
{
/**
     * View instances
     *
     * @var Collection
     */
    public $views;

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
     * Register blocks.
     *
     * Called on init.
     *
     * @return void
     */
    public function register(): RegistrarInterface
    {
        Collection::make($this->container->get('blocks'))->each(
            function ($block) {
                $block->view->render($block);
            }
        );

        return $this;
    }
}
