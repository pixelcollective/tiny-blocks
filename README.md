# Block Modules

Plugin backbone for modular block building.

`Block Modules` allows you to separate blocks or small sets of blocks into individuated plugins, should you not want to put all of your eggs into one basket. All of these blocks are rendered using the Laravel Blade templating engine. If you want to know more about the general idea, check out my [Block Compose library](https://github.com/kellymears/blockcompose). This is the same idea, but using an arguably safer, distributed appproach.

This plugin also lets you render blocks using Blade _even if you don't have access to it otherwise_ (as in: outside of a Roots context).

Both this and `Block Compose` are works in progress. `BlockCompose` is intended for use with [Roots` Clover plugin framework](https://roots.io/clover/), which is still in alpha. It's likely that primary development of this plugin will be completed sooner than that.

## Usage

- Install this.

- Scaffold your block plugin:

```bash
plugins/example-block
├── example-block.php
└── src
    ├── blade
    │   └── render.blade.php
    └── index.js
```

The important bit is that the registration process assumes your blade partials and JS can be found in the `{plugin-name}/src` directory.

## Register a block

```php
add_filter('register_blockmodules', function ($blocks) {
    $blocks->push([
        'plugin' => 'example-block',
        'handle' => 'example/block',
        'entry'  => 'index.js',
        'blade'  => 'blade/render',
    ]);

    return $blocks;
});
```

- `plugin` should match the name of your plugin's directory.
- `handle` should match the handle you used in your JS registration
- `entry` is your main JS file, relative to `{plugin-name}/src`
- `blade` is your blade view for the frontend, relative to `{plugin-name}/src`.

## Blade for everyone

If `Roots\view()` is available the blade templates will be rendered using that. If it is not available, my condolences, but you can still use Blade thanks to [EFTEC\BladeOne](https://github.com/EFTEC/BladeOne), a minimalist blade implementation which will be automatically substituted.

## Example

Certainly. Example usage is included in the `example-block` dir of this repo.

&copy; 2019 tiny pixel collective, llc

licensed MIT
