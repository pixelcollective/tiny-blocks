const mix = require('laravel-mix')

require('laravel-mix-wp-blocks')

const assets = {
  scripts: {
    blocks: [`blocks`],
    react:  [`react`],
    es6:    [`script`],
  },
  styles: [
    `blocks`,
  ],
}

const script = {
  src: file => `resources/blocks/assets/scripts/${file}.js`,
  pub: file => `dist/scripts/${file}.js`,
}

const style = {
  src: file => `resources/blocks/assets/styles/${file}.scss`,
  pub: file => `dist/styles/${file}.css`,
}

if(assets.scripts) {
  assets.scripts.blocks && assets.scripts.blocks.forEach(asset => {
    mix.block(script.src(asset), script.pub(asset))
  })

  assets.scripts.react && assets.scripts.react.forEach(asset => {
    mix.react(script.src(asset), script.pub(asset))
  })

  assets.scripts.es6 && assets.scripts.es6.forEach(asset => {
    mix.js(script.src(asset), script.pub(asset))
  })
}

assets.styles && assets.styles.forEach(asset => {
  mix.sass(style.src(asset), style.pub(asset))
})
