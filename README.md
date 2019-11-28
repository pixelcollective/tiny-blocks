# Block Modules Framework

This plugin is a backbone for building modular blocks with Blade templating support.

This plugin is a work-in-progress.

## Example Plugin structure

The following structure conforms to the default values used by the registrations classes.

```bash
├── block.php                # Registration filters
├── dist                     # Compiled assets
|   ├── scripts
|   |   ├── editor.js        # Compiled editor scripts
|   |   └── public.js        # Compiled public scripts
|   └── styles
|       ├── editor.css       # Compiled editor styles
|       └── public.css       # Compiled public styles
├── package.json
├── phpcs.xml
├── resources
│   ├── assets
│   │   ├── scripts
│   │   │   ├── editor.js    # Block editor scripts
|   |   |   └── public.js    # Public scripts
│   │   └── styles
│   │   │   ├── editor.scss  # Block editor styles
|   |   |   └── public.scss  # Public styles
│   └── views
|       └── block.blade.php  # Block template
├── webpack.mix.js
└── yarn.lock
```

## Using the blockmodules filter

Registration is done using the `blockmodules` filter. Everything can be done within a single filter callback. Multiple plugins can extend the same instance of the Block Modules framework. The Block Modules classes have several actions hooked on `init`, so registration needs to take place before it fires.

Registration is a two part process.

1. Register a "plugin".

2. Register a block against that plugin.

The `blockmodules` filter passes an instance of `BlockModules\Registrar`. This is used to register the plugins and blocks.

## Register a plugin

> `Registrar\addPlugin(string $pluginName, array $configuration)`

```php
add_filter('blockmodules', function ($registrar) {
  $registrar->addPlugin('tiny-pixel-blocks', [
    'path'  => dirname(__FILE__),
    'dir'   => 'tiny-pixel-blocks',
  ]);
});
```

At minimum, a plugin configuration should specify

- `path`: The path to the plugin.
- `dir`: The plugin dir name.

## Register a block

> `Registrar\addBlock(string $pluginName, array $configuration)`

```php
add_filter('blockmodules', function ($registrar) {
  $registrar->addBlock('tiny-pixel-blocks', [
    'handle'    => 'tiny-pixel/block',
  ]);
});
```

At minimum, a block configuration should specify

- `handle`: The block handle used in JS.

This will work assuming the layout of the plugin is handled with the exact same structure as the one illustrated at the beginning of this document. However, you can optionally specify many other options.

As an example, here is a plugin with blocks nested in subdirectories.

```php
/**
 * Plugin Name: Tiny Pixel Blocks
 * Description: WordPress block editor components
 */
add_filter('blockmodules', function ($registrar) {
    $registrar->addPlugin('tiny-pixel-blocks', [
        'file'  => dirname(__FILE__),
        'dir'  => 'tiny-pixel-blocks',
    ]);

    $registrar->addBlock('tiny-pixel-blocks', [
        'handle'    => 'tiny-pixel/block',
        'dir'       => 'block',            // Path to block (relative to plugin root)
        'filepaths' => [
            'views' => 'resources/views',  // Path to block views (relative to block root)
            'dist'  => 'dist',             // Path to block dist (relative to block root)
        ],
        'editor'  => [
            'js'  => 'scripts/editor',     // Path to editor scripts (relative to dist)
            'css' => 'styles/editor',      // Path to editor styles (relative to dist)
        ],
        'public'  => [
            'js'  => 'scripts/public',     // Path to public scripts (relative to dist)
            'css' => 'styles/public',      // Path to public styles (relative to dist)
        ],
        'view' => 'block.blade.php',       // Filename of the main view file
    ]);
});
```

## Views

Views have two variables made available to them by default: `$attr` and `$content`.

- `$attr` is an object containing all the block attributes.
- `$content` contains nested markup from `InnerBlocks`.

```php
@isset($attr->heading)
  <h2>
    {!! $attr->heading !!}
  </h2>
@endif

@isset($attr->accentText)
  <span>
    {!! $attr->accentText !!}
  </span>
@endif

@isset($content)
  <div class="innerBlocks">
    {!! $content !!}
  </div>
@endif
```

## Additional filters

Modify where cached views are stored:

```php
add_filter('blockmodules_cache', function ($cachePath) {
  return '/cache/to/this/dir';
});
```

Change the base directory used to located view templates:

```php
add_filter('blockmodules_views', function ($basePath) {
  return '/views/relative/from/this/dir';
});
```

Disable `@user` and `@guest` directives

```php
add_filter('blockmodules_disable_user', function () {
  return true;
});
```

Enable debug mode

```php
add_filter('blockmodules_debug', function () {
  return true;
})
```

## Notes

### Blade implementation

For Blade functionality, `BlockModules` utilizes [EFTEC\BladeOne](https://github.com/EFTEC/BladeOne), a minimalist Blade implementation with zero dependencies.

### Author notes

&copy; 2019 Tiny Pixel Collective

[Licensed MIT](https://github.com/pixelcollective/tree/master/LICENSE.md).
