// @wordpress
import { __ } from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks'

// components
import { anuddahEdit } from './components'

// block attributes
const attr = {
  text: {
    type: `string`,
  },
  media: {
    type: `object`
  },
}

registerBlockType(`tinypixel/anuddah`, {
  title: __(`AnudahWon`),
  category: `common`,
  icon: `plugin`,
  attributes: attr,
  edit: anuddahEdit,
  save: () => null,
})
