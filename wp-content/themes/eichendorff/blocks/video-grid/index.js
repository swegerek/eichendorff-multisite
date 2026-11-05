// Video grid: holds only Film blocks; the appender at the end adds the next film.
( function ( wp ) {
	const { createElement: el } = wp.element;
	const { useBlockProps, useInnerBlocksProps, InnerBlocks } = wp.blockEditor;

	wp.blocks.registerBlockType( 'eichendorff/video-grid', {
		edit: function Edit() {
			const innerBlocksProps = useInnerBlocksProps( useBlockProps(), {
				allowedBlocks: [ 'eichendorff/film' ],
				template: [ [ 'eichendorff/film' ] ],
				renderAppender: InnerBlocks.ButtonBlockAppender,
			} );

			return el( 'div', innerBlocksProps );
		},

		save: function save() {
			return el( 'div', useInnerBlocksProps.save( useBlockProps.save() ) );
		},
	} );
} )( window.wp );
