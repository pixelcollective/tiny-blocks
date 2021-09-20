const mix = require("laravel-mix");

require("laravel-mix-wp-blocks");

mix.setResourceRoot("./resources/assets").setPublicPath("./dist");

mix
  .sass("./resources/assets/styles/editor.scss", "./dist/styles/editor.css")
  .sass("./resources/assets/styles/public.scss", "./dist/styles/public.css");

mix.block("./resources/assets/scripts/editor.js", "./dist/scripts/editor.js");
