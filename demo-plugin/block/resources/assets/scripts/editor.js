// @wordpress
import { __ } from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks'
import { InnerBlocks } from '@wordpress/block-editor'

// components
import { edit } from './components/edit'

// registration
registerBlockType(`tiny-pixel/block`, {
  title: __(`Block`, `tiny-pixel`),
  category: `common`,
  icon: `hammer`,
  attributes: {
    heading: {
      type: `string`,
    },
  },
  supports: {
    align: true,
  },
  edit,
  save: () => <InnerBlocks.Content />,
})
