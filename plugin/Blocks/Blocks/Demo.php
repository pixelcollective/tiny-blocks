<?php
namespace Plugin\Blocks\Blocks;

use TinyPixel\Base\Blocks\Block;

class Demo extends Block
{
    /**
     * Name of this block.
     *
     * @var string
     */
    public $name = 'demo';

    /**
     * View used to render block.
     *
     * @var string
     */
    public $view = 'block::demo';

    /**
     * Data to be passed to the block before rendering.
     *
     * @param  array $data
     * @return array
     */
    public function with($block) : array
    {
        return [
            'attributes' => $block['attributes'],
            'content'    => $block['content'],
        ];
    }
}
