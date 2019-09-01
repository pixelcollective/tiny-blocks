// @wordpress
import { __ } from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks'

// components
import { projectEdit } from './components'

// block attributes
const attr = {
  text: {
    type: `string`,
  },
  media: {
    type: `object`
  },
}

registerBlockType(`tinypixel/project`, {
  title: __(`Project`),
  category: `common`,
  icon: `plugin`,
  attributes: attr,
  edit: projectEdit,
  save: () => null,
})
