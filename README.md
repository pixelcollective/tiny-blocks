# Block Modules Framework

This plugin is a backbone for building modular blocks with Blade templating support.

This plugin is a work-in-progress.

## Example Plugin structure

The following structure conforms to the default values used by the registration classes.

```bash
├── block.php                # Registration filters
├── dist                     # Compiled assets
│   ├── scripts
│   │   ├── editor.js        # Compiled editor scripts
│   │   └── public.js        # Compiled public scripts
│   └── styles
│       ├── editor.css       # Compiled editor styles
│       └── public.css       # Compiled public styles
├── package.json
├── phpcs.xml
├── resources
│   ├── assets
│   │   ├── scripts
│   │   │   ├── editor.js    # Block editor scripts
│   │   │   └── public.js    # Public scripts
│   │   └── styles
│   │   │   ├── editor.scss  # Block editor styles
│   │   │   └── public.scss  # Public styles
│   └── views
│       └── block.blade.php  # Block template
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

> `$registrar->addPlugin($pluginName, [...$configuration])`

| Parameter                          | Type   | Description                  |
|------------------------------------|--------|------------------------------|
| `$pluginName`                      | string | Used to reference the plugin |
| `$configuration`                   | array  | Configure the plugin         |

Currently, the only configuration key used by the framework is `dir`.

```php
add_filter('blockmodules', function ($registrar) {
  $registrar->addPlugin('tiny-pixel-blocks', [
    'dir' => 'tiny-pixel-blocks',
  ]);
});
```

`dir` is the path to the pluign relative to the framework's base path (the default framework base path resolves to the plugins directory, but this is configurable with the `blockmodules_base` filter, detailed later in this document.)

## Register a block

> `Registrar\addBlock(string $pluginName, array $configuration)`

| Parameter        | Type   | Description               |
|------------------|--------|---------------------------|
| `$pluginName`    | string | The plugin reference name |
| `$configuration` | array  | Block configuration       |

The first parameter specifies the name given to the plugin using `\Registrar\addPlugin()`. The second parameter is a keyed array used for configuring the block. At minimum, this configuration should specify:

- `handle`: The block handle used in JS.

```php
add_filter('blockmodules', function ($registrar) {
  $registrar->addBlock('tiny-pixel-blocks', [
    'handle' => 'tiny-pixel/block',
  ]);
});
```

This minimal implementation works _assuming the file structure of the block mirrors the framework's default expectations_, illustrated at the beginning of this document and in the following table:

| File                               | Description             | Notes    |
|------------------------------------|-------------------------|----------|
| `/plugin.php`                      | Plugin registration     |          |
| `/resources/views/block.blade.php` | Block view              |          |
| `/dist/scripts/editor.js`          | Compiled editor scripts |          |
| `/dist/scripts/editor.asset.php`   | WP dependency manifest  |          |
| `/dist/scripts/public.js`          | Compiled public scripts | Optional |
| `/dist/styles/editor.css`          | Compiled public styles  | Optional |
| `/dist/styles/public.css`          | Compiled public styles  | Optional |

In addition to the minimal implementation provided by the default configuration, there are numerous configuration options which can be specified in the keyed array passed as the secondary parameter to `Registrar\addBlock()`.

As an example, here is a plugin with blocks nested in subdirectories, with comments indicating the function of each key/value:

```php
/**
 * Plugin Name: Tiny Pixel Blocks
 * Description: WordPress block editor components
 */
add_filter('blockmodules', function ($registrar) {
    $registrar->addPlugin('tiny-pixel-blocks', [
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
      'view'   => 'block.blade.php',       // Filename of the main view file
      'layout' => 'block-modules/resources/views/layouts/block.blade.php'
  ]);
});
```

## Views

The framework provides block data to the view via the following variables: `$attr`, `$layout`, `$content` and `$classname`.

### Block attributes: `$attr`

Attributes defined in the block's JS are passed to the view with `$attr`. Attributes can be single or multi-dimensional.

**`$attr->heading`**

```js
const attributes = {
  heading: {
    type: `string`,
    default: `Hello, world.`,
  },
}
```

**`$attr->image['url']`**

```js
const attributes = {
  image: {
    type: `object`,
  },
}
```

If your block supports `alignment`, you will find `align` here as well.

### Block InnerContent: `$content`

Nested markup from `<InnerBlocks />` is passed to the view with `$content`.

### Block master layout reference: `$layout`

This is a reference to the block's master layout file. By default this file is `block-modules/resources/views/layouts/block.blade.php`.

It can be used with the `@extends` Blade directive.

The default layout wraps the block view in a div with the standard block classname and alignment classes.

You can also supply your own by specifying a `layout` key in during block registration:

```php
$registrar->addBlock('tiny-pixel-blocks', [
  'handle' => 'tiny-pixel/block',
  'layout' => 'block-modules/resources/views/layouts/block.blade.php'
]);
```

### Block classname: `$classname`

This is just a preformulated block classname.

### Directives

Most of Laravel Blade's helper directives are available to a block view (`@dd`, `@isset`, `@if`, `@foreach`, etc.). For a complete list see the documentation for [BladeOne](https://github.com/EFTEC/BladeOne).

There are also some additional block-specific directives:

#### `@auth` and `@endauth`

`@auth` and `@endauth` function as a control structure, only rendering their contents if a user is logged-in.

```php
@auth
  <h2>Hello, logged in user!</h2>
@endauth
```

Additionally, a role can be passed as a secondary parameter:

```php
@auth('admin')
  <h2>Hello, administrator!</h2>
@endauth
```

### Example view

The following view presents single and multidimensional attributes along with `<InnerBlocks />` content.

**`/resources/views/block.blade.php`**

```php
@isset($attr->heading)
  <h2>
    {!! $attr->heading !!}
  </h2>
@endif

@isset($attr->image)
  <img src="{!! \get_attachment_url($attr->image['id']) !!}" alt="{!! $attr->image['alt'] !!}" />
@endif

@isset($content)
  <div class="innerBlocks">
    {!! $content !!}
  </div>
@endif
```

## Global configuration filters

These filters effect all registered blocks.

### Modify where cached views are stored:

> Default: `wp_upload_dir()['basedir'] . '/uploads/block/cache'`

```php
add_filter('blockmodules_cache', function ($cachePath) {
  return '/srv/www/mysite.com/current/app/custom/view/cache/';
});
```

For example, when storing uploads on Amazon S3 or Digital Ocean Spaces, the default cache path is actually an `s3://` url, which causes problems. In order to store views on the local filesystem, you could apply a filter which doesn't reference `wp_upload_dir()`:

```php
add_filter('blockmodules_cache', function ($cacheDir) {
    return realpath(__DIR__ .'/../../uploads/blocks/cache/');
});
```

### Change the block-modules base directory.

> Default: `WP_PLUGIN_DIR`

```php
add_filter('blockmodules_base', function ($basePath) {
  return '/views/relative/from/this/dir';
});
```

### Disable `@user` and `@guest` directives

> Default: `false`

```php
add_filter('blockmodules_disable_user', function () {
  return true;
});
```

### Enable debug mode

> Default `false`

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
