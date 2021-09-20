import { __ } from "@wordpress/i18n";
import { InnerBlocks, RichText } from "@wordpress/block-editor";

export const edit = ({ className, attributes, setAttributes }) => {
  const { heading } = attributes;

  const onChange = {
    heading: (heading) => setAttributes({ heading }),
  };

  return (
    <div className={className}>
      <div className={`${className}__column-a`}>
        <RichText
          el={`div`}
          className={`${className}__column-a-heading`}
          value={heading && heading}
          onChange={onChange.heading}
        />
      </div>

      <div className={`${className}__column-b`}>
        <InnerBlocks />
      </div>
    </div>
  );
};
