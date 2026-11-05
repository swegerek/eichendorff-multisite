// Trail list: holds only Trail cards; the appender at the end adds the next trail.
( function ( wp ) {
	const { createElement: el } = wp.element;
	const { useBlockProps, useInnerBlocksProps, InnerBlocks } = wp.blockEditor;

	wp.blocks.registerBlockType( 'eichendorff/trail-list', {
		edit: function Edit() {
			const innerBlocksProps = useInnerBlocksProps( useBlockProps(), {
				allowedBlocks: [ 'eichendorff/trail-card' ],
				template: [ [ 'eichendorff/trail-card' ] ],
				renderAppender: InnerBlocks.ButtonBlockAppender,
			} );

			return el( 'div', innerBlocksProps );
		},

		save: function save() {
			return el( 'div', useInnerBlocksProps.save( useBlockProps.save() ) );
		},
	} );
} )( window.wp );
