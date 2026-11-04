// Makes the Site Editor preview match the front end for event dates and hand-written excerpts.
( function ( wp ) {
	const { createElement: el } = wp.element;

	wp.blocks.registerBlockBindingsSource( {
		name: 'eichendorff/event-date',
		label: 'Data wydarzenia',
		usesContext: [ 'postId', 'postType' ],
		getValues( { select, context, bindings } ) {
			const event = context.postId
				? select( 'core' ).getEntityRecord( 'postType', context.postType, context.postId )
				: null;
			const date = event?.meta?.event_date;
			const values = {};

			Object.entries( bindings ).forEach( ( [ attribute, { args } ] ) => {
				values[ attribute ] = date ? wp.date.dateI18n( args?.format ?? 'j F Y', `${ date }T12:00:00` ) : '';
			} );

			return values;
		},
	} );

	const ExcerptPreview = ( { html } ) => {
		const blockProps = wp.blockEditor.useBlockProps( {
			dangerouslySetInnerHTML: { __html: wp.dom.safeHTML( wp.autop.autop( html ) ) },
		} );

		return el( 'div', blockProps );
	};

	const FullExcerpt = ( { BlockEdit, ...props } ) => {
		const { postId, postType } = props.context;
		const [ excerpt ] = wp.coreData.useEntityProp( 'postType', postType, 'excerpt', postId );

		return excerpt ? el( ExcerptPreview, { html: excerpt } ) : el( BlockEdit, props );
	};

	wp.hooks.addFilter(
		'editor.BlockEdit',
		'eichendorff/full-excerpt',
		wp.compose.createHigherOrderComponent(
			( BlockEdit ) => ( props ) => {
				if ( 'core/post-excerpt' !== props.name || ! props.context?.postId ) {
					return el( BlockEdit, props );
				}

				return el( FullExcerpt, { ...props, BlockEdit } );
			},
			'withFullExcerpt'
		)
	);
}( window.wp ) );
