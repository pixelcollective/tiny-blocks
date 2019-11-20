# Block Modules

Plugin backbone for modular block building using Laravel Blade and React.

## Usage

- Install this.

- Scaffold your block plugin:

```bash
plugins/example-block
├── example-block.php
└── src
    ├── blade
    │   └── render.blade.php
    └── scripts
        └── editor.js
```

## Register a block

```php
<?php
/**
 * Plugin name: Example block
 */
add_filter('register_blockmodules', function ($blocks) {
    $blocks->push([
        'dir'    => __DIR__,
        'handle' => 'yolo/block',
        'editor' => ['js' => 'scripts/editor'],
    ]);

    return $blocks;
});
```

## Write a view

Now you can use the specified blade partial to render your block. You'll find the block attributes and inner content already waiting for you.

```php
<h2>{!! $attr->heading !!}</h2>
<span>{!! $attr->accentText !!}</span>

<div class="innerBlocks">
  {!! $content !!}
</div>
```

## Tweak settings with filters (optional)

Modify where cached views are stored:

```php
add_filter('cache_path_blockmodules', function ($cachePath) {
  return '/cache/to/this/dir';
});
```

Change the base directory used to located view templates:

```php
add_filter('base_path_blockmodules', function ($basePath) {
  return '/views/relative/from/this/dir';
});
```

Disable user functions (saves the database call):

```php
add_filter('disable_user_blockmodules', function () {
  return true;
});
```

Enable view debugger:

```php
add_filter('debug_blockmodules', function () {
  return true;
})
```

## Blade for everyone

Under the hood this plugin uses [EFTEC\BladeOne](https://github.com/EFTEC/BladeOne), a minimalist blade implementation that does not require any particular plugin or framework and has zero dependencies.

&copy; 2019 tiny pixel collective, llc

licensed MIT
