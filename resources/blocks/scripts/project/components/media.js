// @wordpress
import { __ } from '@wordpress/i18n'
import { MediaPlaceholder } from '@wordpress/block-editor'

const Media = props => {
  const labels = {
    title: props.title && props.title,
    instructions: props.instructions && props.instructions,
  }

  return (
    <MediaPlaceholder
      labels={labels}
      value={props.image && props.image}
      onSelect={props.onMedia}
      allowedTypes={props.allowed && props.allowed}
      className={`project__media-image`} />
  )
}

export default Media