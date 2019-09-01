<?php
namespace Plugin\Blocks\Assets;

use TinyPixel\Base\Blocks\Assets;

class Demo extends Assets
{
    /**
     * Internally identifies assets.
     *
     * @var array
     */
    public $name = 'demo';

    /**
     * Conditionally load assets with listed block(s).
     *
     * You may remove this declaration should you desire
     * to load assets irrespective of block presence.
     *
     * @var array
     */
    public $blocks = ['demo'];

    /**
     * Assets used in the editor interface.
     *
     * @var array
     */
    public $editor = [
        'scripts' => ['blocks.js'],
        'styles'  => ['blocks.css'],
    ];

    /**
     * Assets used in the application.
     *
     * @var array
     */
    public $app = [
        'scripts' => ['app.js'],
        'styles'  => ['app.css'],
    ];
}
