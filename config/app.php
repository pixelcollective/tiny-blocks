<?php

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
      'root_dir' => realpath(__DIR__ . '/..'),
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
         'dir'   => realpath(__DIR__ . '/../'),
         'cache' => wp_upload_dir()['basedir'] . '/app',
         'debug' => 0,
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

   'blocks' => [],

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
      \TinyPixel\Blocks\View::class => function (Psr\Container\ContainerInterface $app) {
         return new \TinyPixel\Blocks\View($app);
      },

      \TinyPixel\Blocks\Block::class => function (Psr\Container\ContainerInterface $app) {
         return new \TinyPixel\Blocks\Block($app);
      },

      \TinyPixel\Blocks\Assets::class => function (Psr\Container\ContainerInterface $app) {
         return new \TinyPixel\Blocks\Assets($app);
      },

      \TinyPixel\Blocks\Asset::class => function (Psr\Container\ContainerInterface $app) {
         return new \TinyPixel\Blocks\Asset($app);
      },

      \TinyPixel\Blocks\Registrar::class => function (Psr\Container\ContainerInterface $app) {
         return new \TinyPixel\Blocks\Registrar($app);
      },
   ],
];
