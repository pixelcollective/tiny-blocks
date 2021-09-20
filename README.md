# tiny-pixel/blocks

Provides a backbone for building modular blocks with Blade templating support.

This framework is under active development.

## What this is

- A way to streamline and structure the registration of blocks
- A way to streamline and structure the registration of block assets
- A provider of Blade functionality and templating utilities for server-rendered, dynamic blocks.

## What this is not

- A way of writing editor blocks without javascript

## Getting started

Use `composer` to install the package to your project:

```sh
composer require tiny-pixel/blocks
```

## Usage

See the demo directory in the repo for an example implementation.

File structure:

```sh
demo
├── config
│   └── app.php # Plugin config
├── index.php # Plugin entrypoint
├── resources
│   ├── assets # JS and CSS assets
│   │   # Implementation is up to you
│   │
│   └── views # Place blade templates here
│       └── Demo # Should match block name
│           └── block.blade.php # Entry template
│      
└── src # Block classes
    └── Demo.php # Match name of views & class
```

Somewhere in your plugin, hook into the `tinypixel/blocks/loaded` action.

It passes you an instance of the library. Call `main` to kick everything off.
You must pass the path to your config directory to `main`.

```php
add_action('tinypixel/blocks/loaded', function ($app) {
    $configPath = realpath(__DIR__ . '/config');
    $app->main($configPath);
});
```

In `config/app.php`, you only really need to do two things:

1. Add your blocks to the classes in the `blocks` key:

```php
   'blocks' => [
      \Foo\Demo::class,
   ],
```

2. Change the `project.domain` key to match the namespace of your blocks. Not the PHP namespace, but the block namespace used in `register_block_type`.

```php
   'project' => [
        'domain' => 'foo',
        'base_path' => realpath(__DIR__ . '/../'),
        'base_url' => plugins_url('/', __DIR__),
        'dist' => 'dist',
   ],
```

You can modify the other settings as you see fit. They are largely self explanatory and control where caches are stored, where compiled assets are being stored, etc. One day this might even be documented!

## Block definitions

By default, the library will look for block classes in `src/`.

This classname should match the name of the block without the block namespace. For example, for a `foo/demo` namespace we will register the class as `Demo`. The namespace is set in `config.project.domain`, as mentioned above.

Use the `setupAssets` method to register your block scripts and styles.

In addition to the ones below you can use `$this->addPublicScript` and `$this->addPublicStyle` to register frontend scripts and styles.

```php
<?php

namespace Foo;

use TinyPixel\Blocks\Block;

class Demo extends Block
{
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
```

Last step (other than actually writing the block JS, good luck!) is to implement the template. The default template path is `resources/views`. The block template should be named `block.blade.php`. It should be located in a directory named after the block name.

Any block attributes are available in the template as `$attr`. These attributes are passed to the block as an object.

In addition to the `$attr` object, you also have access to `$content`, which is a `string` of the block content. You also have access to a `$id` and `$className` variable, for convenience.

The `$id` is a unique identifier for the block. You can use it for dynamically applying styles to the block, or calling JS functions.

The `$className` is the class name of the block. This is based on the block name and follows the following naming convetion: `wp-block-{namespace}-{name}`.

## Author notes

&copy; 2019 Tiny Pixel Collective

[Licensed MIT](https://github.com/pixelcollective/tree/master/LICENSE.md).
