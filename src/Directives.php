<?php

namespace TinyPixel\Modules;

use function \add_action;
use function \register_block_type;

use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;

/**
 * Directives
 *
 * @since   0.4.0
 * @version 0.4.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Directives
{
    public function __construct(Blade $blade, array $config)
    {
        $this->blade = $blade;
        $this->config = $config;
    }
}
