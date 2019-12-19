# Tiny Blocks Framework

Provides a backbone for building modular blocks with Blade templating support.

This framework is under active development.

## What this is

- A way to streamline and structure the registration of blocks
- A way to streamline and structure the registration of block assets
- A provider of Blade functionality and templating utilities

## What this is not

- A way of writing editor blocks without javascript

## Getting started

Use `composer` to install the package to your project:

```sh
composer require tinypixel/tinyblocks
```

## Block registration

### Object-oriented approach

This is the preferred method.

**Add a pre-defined block:**

```php
use \TinyBlocks\App;

/** initialize framework */
$tinyblocks = App::getInstance();
$tinyblocks->initialize();

/** add pre-defined block */
$tinyblocks->addBlock(\Demo\DemoBlock::class);
```

**The example class added above:**

```php
namespace Demo;

use TinyBlocks\Base\Block;

class DemoBlock extends Block
{
    /** block name */
    public $name = 'tinyblocks/demo';

    /** view instance */
    public $view = 'tinyblocks';

    /** template file */
    public $template = 'block';

    /** classnames */
    public $className = 'wp-block-demo';

    /**
     * Data to be passed to the block view.
     */
    public function with(array $data): array
    {
      // A great place to query an API, etc.
      return $data;
    }

    /**
     * Set and define assets
     */
    public function setupAssets(): void
    {
      /** makeAsset factory method produces TinyBlock\Asset */
      $editorStyle = $this->makeAsset();
      $editorStyle->setName('demo/css')
        ->setUrl(plugins_url() . '/demo/dist/styles/editor.css');

      $editorScript = $this->makeAsset();
      $editorScript->setName('demo/js')
        ->setUrl(plugins_url() . '/demo/dist/scripts/editor.js')
        ->setManifest(WP_PLUGIN_DIR . '/demo/dist/scripts/editor.asset.php');

      /** Add assets to the block */
      $this->addEditorStyle($editorStyle);
      $this->addEditorScript($editorScript);
    }
}
```

### Functional approach

```php
use \TinyBlocks\App;

/** initialize framework */
$tinyblocks = App::getInstance();
$tinyblocks->initialize();

/** make and define a new block */
$myBlock = $tinyblocks->make();
$myBlock->setName('tinyblock/demo');
$myBlock->setView('plugins');
$myBlock->setTemplate('demo/resources/views/block.blade.php');
$myBlock->setClassName('wp-block-demo');

/** define a script */
$script = $myBlock->makeAsset()
    ->setName('tinyblocks/demo/js')
    ->setUrl(WP_PLUGIN_DIR . '/demo/dist/editor.js')
    ->setManifest(plugins_url() . '/demo/dist/editor.manifest.php');

/** add script to block */
$myBlock->addEditorScript($script);

/** finalize */
$tinyblocks->addBlock($myBlock);
```

## Configuration

Substitute configuration files in `App::getInstance()`.

**Example:**

```php
App::getInstance(__DIR__ . '/config');
```

### views.php

The default view configuration is as follows:

```php
return [

  /*
  |--------------------------------------------------------------------------
  | View engine instances
  |--------------------------------------------------------------------------
  |
  | Here you may specify one or more locations on disk to register as
  | a views directory. When using the standard default eftec\blade
  | configuration this yields a separate view engine instance per location
  | specified. Other view implementations may work differently.
  |
  */

  'views' => [
    'wordpress' => [
        'dir'   => WP_CONTENT_DIR,
        'cache' => $cachePath,
        'debug' => 0,
    ],

    'plugins' => [
        'dir' => WP_PLUGIN_DIR,
        'cache' => $cachePath,
        'debug' => 0,
    ],
  ],
];
```

Views are specified when making a `Block` by its label. **Example:**

```php
$myBlock->setView('plugins');
```

The `app/plugins` directory is now the root template directory. Whatever template is specified in the block will now be resolved relative to this location:

```php
// resolves to PLUGINS_DIR/demo/view.blade.php
$myBlock->setTemplate('demo/view');
```

In this manner, you may specify as many view directories as you wish.

Note that each specification instantiates a new `View`, so there is a cost to this. However, the `View` will only be instantiated if it is actually utilized by a `Block`, so defining many locations in the `config/views.php` file alone should not be a cause of performance issues.

Many `Block` instances can use a share one `View` instance.

### providers.php

Allows for substituting core class components. This should make it possible to author an alternative `View` implementation for individuals wishing to use this framework in a Sage theme with `Illuminate\View`. But you can substitute anything you see fit.

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | The following class definitions are used by the core classes when
    | registering blocks and rendering views. Here you may register your own
    | class definitions or substitute in alternative implementations of
    | core services (for example: using Illuminate\View instead of eftect\BladeOne)
    |
    */

    /** @see TinyBlocks\Contracts\ApplicationInterface */
    'application' => function () {
        return App::getInstance();
    },

    /** @see TinyBlocks\Contracts\ViewInterface */
    'view' => function (Container $app) {
        return new View($app);
    },

    /** @see TinyBlocks\Contracts\BlockInterface */
    'block' => function (Container $app) {
        return new Block($app);
    },

    /** @see TinyBlocks\Contracts\AssetsInterface */
    'assets' => function (Container $app) {
        return new Assets($app);
    },

    /** @see TinyBlocks\Contracts\AssetInterface */
    'asset' => function (Container $app) {
        return new Asset($app);
    },

    /** @see TinyBlocks\Contracts\RegistrarInterface */
    'registrar' => function (Container $app) {
        return new Registrar($app);
    },
];
```

## Author notes

&copy; 2019 Tiny Pixel Collective

[Licensed MIT](https://github.com/pixelcollective/tree/master/LICENSE.md).
