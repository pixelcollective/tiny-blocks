<?php

/**
 * Plugin Name: Example Block
 * Description: Example implementation of tiny-blocks
 */

version_compare($GLOBALS['wp_version'], '5.0', '<') ?:

  add_filter('register_tinyblock', function ($blocks) {
      $blocks->push([
          'plugin' => 'example-block',
          'handle' => 'example/block',
          'entry'  => 'index.js',
          'blade'  => 'blade/render',
      ]);

      return $blocks;
  });
