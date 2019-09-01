<?php

namespace Plugin\Console\Commands;

use TinyPixel\Base\Console\Commands\Command;

class BlockMakeCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:block {name* : The name of your Block.}
                           {--views= : List of views served by the composer}
                           {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Block';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return [
            __DIR__ . '/stubs/block/block.stub',
            __DIR__ . '/stubs/block/components/edit.stub',
            __DIR__ . '/stubs/block/components/index.stub',
            __DIR__ . '/stubs/block/components/media.stub',
        ];
    }

    /**
     * Determine if file already exists
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath("resources/assets/scripts/{$rawName}"));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceDummies($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceDummies(&$stub, $name)
    {
        $stub = str_replace('dummyBlockName', $name, $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  array   $views
     * @return string
     */
    protected function replaceViews($stub, $views)
    {
        $views = implode("',\n        '", $views);

        return str_replace('DummyViews', empty($views) ? '//' : "'{$views}'", $stub);
    }
}
