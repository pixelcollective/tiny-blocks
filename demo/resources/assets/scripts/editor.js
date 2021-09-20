import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";
import { InnerBlocks } from "@wordpress/block-editor";

import { edit } from "./components/edit";

registerBlockType(`tinyblocks/demo`, {
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
});
