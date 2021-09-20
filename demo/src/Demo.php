<?php

namespace Foo;

use TinyPixel\Blocks\Block;

class Demo extends Block
{
    /**
     * Data to be passed as variables to the view.
     */
    public function with(array $data): array
    {
        return $data;
    }

    /**
     * Setup assets
     */
    public function setupAssets(): void
    {
        $this->addEditorStyle(
            $this->makeAsset('editor/css')
                ->setUrl('dist/styles/editor.css')
        );

        $this->addEditorScript(
            $this->makeAsset('editor/js')
                ->setUrl('dist/scripts/editor.js')
        );
    }
}
