# Tiny Block Modules

I don't like putting all my blocks in one plugin. I made this helper so I can separate blocks or small sets of blocks into individuated plugins. This will also let you render blocks using Blade even if you don't have access to it otherwise.

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
add_filter('register_tinyblock', function ($blocks) {
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

km 2019 // licensed MIT
