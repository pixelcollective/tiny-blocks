const mix = require('laravel-mix')
require('laravel-mix-wp-blocks')
require('laravel-mix-tweemotional')

mix.block('src', 'dist/index.js')
   .tweemotional({tailwind: 'tailwind.config.js'})
