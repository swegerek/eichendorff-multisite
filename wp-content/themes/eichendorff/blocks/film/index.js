// Film: a title and a player, locked together. A new film asks whether it plays a file or a YouTube video.
( function ( wp ) {
	const { createElement: el } = wp.element;
	const { useBlockProps, useInnerBlocksProps } = wp.blockEditor;
	const { Placeholder, Button } = wp.components;
	const { useSelect, useDispatch } = wp.data;
	const { createBlock } = wp.blocks;

	const title = () => createBlock( 'core/heading', { level: 3, placeholder: 'Tytuł filmu' } );

	const sources = [
		{ label: 'Z pliku', player: () => createBlock( 'core/video' ) },
		{ label: 'Z YouTube', player: () => createBlock( 'core/embed', { providerNameSlug: 'youtube', responsive: true } ) },
	];

	wp.blocks.registerBlockType( 'eichendorff/film', {
		edit: function Edit( { clientId } ) {
			const blockProps = useBlockProps();
			const hasPlayer = useSelect( ( select ) => select( 'core/block-editor' ).getBlockCount( clientId ) > 0, [ clientId ] );
			const { replaceInnerBlocks } = useDispatch( 'core/block-editor' );
			const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: 'all' } );

			if ( hasPlayer ) {
				return el( 'div', innerBlocksProps );
			}

			return el(
				'div',
				blockProps,
				el(
					Placeholder,
					{ icon: 'video-alt3', label: 'Film', instructions: 'Skąd jest film?' },
					sources.map( ( source ) =>
						el(
							Button,
							{
								key: source.label,
								variant: 'secondary',
								onClick: () => replaceInnerBlocks( clientId, [ title(), source.player() ] ),
							},
							source.label
						)
					)
				)
			);
		},

		save: function save() {
			return el( 'div', useInnerBlocksProps.save( useBlockProps.save() ) );
		},
	} );
} )( window.wp );
