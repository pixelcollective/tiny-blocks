<?php

$cachePath = __DIR__ . '/../../../uploads/blocks';

return [
   /*
   |--------------------------------------------------------------------------
   | Project
   |--------------------------------------------------------------------------
   |
   | Project
   |
   */

   'project' => [
        'domain' => 'tinypixel',
        'base_path' => realpath(__DIR__ . '/../'),
        'base_url' => plugins_url('/', __DIR__),
        'dist' => 'dist',
   ],

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
      'app' => [
         'dir'   => realpath(__DIR__ . '/../resources/views/'),
         'cache' => $cachePath,
         'debug' => false,
      ],
   ],

   /*
   |--------------------------------------------------------------------------
   | Blocks
   |--------------------------------------------------------------------------
   |
   | Registered blocks
   |
   */

   'blocks' => [
      \Foo\Demo::class,
   ],

   /*
   |--------------------------------------------------------------------------
   | Providers
   |--------------------------------------------------------------------------
   |
   | The following class definitions are used by the core classes when
   | registering blocks and rendering views. Here you may register your own
   | class definitions or substitute in alternative implmentations of
   | core services (for example: using Illuminate\View instead of eftect\BladeOne)
   |
   */

   'providers' => [
      'view' => function (Illuminate\Support\Collection $app) {
         return new \TinyPixel\Blocks\View($app);
      },

      'block' => function (Illuminate\Support\Collection $app) {
         return new \TinyPixel\Blocks\Block($app);
      },

      'assets' => function (Illuminate\Support\Collection $app) {
         return new \TinyPixel\Blocks\Assets($app);
      },

      'asset' => function (Illuminate\Support\Collection $app) {
         return new \TinyPixel\Blocks\Asset($app);
      },

      'registrar' => function (Illuminate\Support\Collection $app) {
         return new \TinyPixel\Blocks\Registrar($app);
      },
   ],
];
