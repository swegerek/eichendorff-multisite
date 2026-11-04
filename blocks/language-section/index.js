// Language section: the language chosen in the sidebar sets the flag, the name and the `lang` of the text.
( function ( wp ) {
	const { createElement: el, useEffect, useState } = wp.element;
	const { useBlockProps, useInnerBlocksProps, InnerBlocks, InspectorControls } = wp.blockEditor;
	const { PanelBody, SelectControl } = wp.components;

	const languages = {
		pl: [ 'Polski', 'flag-pl' ],
		de: [ 'Deutsch', 'flag-de' ],
		cs: [ 'Čeština', 'flag-cz' ],
		en: [ 'English', 'flag-gb' ],
	};

	// Icons from the theme collection, fetched once per name.
	const icons = {};
	const useIcon = ( name ) => {
		const [ svg, setSvg ] = useState( icons[ name ] || '' );

		useEffect( () => {
			if ( icons[ name ] ) {
				setSvg( icons[ name ] );
				return;
			}

			wp.apiFetch( { path: `/wp/v2/icons/${ name }` } )
				.then( ( icon ) => {
					icons[ name ] = icon.content || '';
					setSvg( icons[ name ] );
				} )
				.catch( () => setSvg( '' ) );
		}, [ name ] );

		return svg;
	};

	wp.blocks.registerBlockType( 'eichendorff/language-section', {
		edit: function Edit( { attributes, setAttributes } ) {
			const language = languages[ attributes.language ] ? attributes.language : 'pl';
			const [ name, flag ] = languages[ language ];
			const svg = useIcon( `eichendorff/${ flag }` );
			const innerBlocksProps = useInnerBlocksProps(
				{ className: 'wp-block-eichendorff-language-section__text', lang: language },
				{ allowedBlocks: [ 'core/paragraph', 'core/list', 'core/heading' ], template: [ [ 'core/paragraph' ] ] }
			);

			return el(
				'section',
				useBlockProps(),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: 'Język' },
						el( SelectControl, {
							label: 'Język tekstu',
							value: language,
							options: Object.entries( languages ).map( ( [ value, [ label ] ] ) => ( { value, label } ) ),
							onChange: ( value ) => setAttributes( { language: value } ),
							__nextHasNoMarginBottom: true,
						} )
					)
				),
				el(
					'p',
					{ className: 'wp-block-eichendorff-language-section__name' },
					el( 'span', { className: 'wp-block-eichendorff-language-section__flag', dangerouslySetInnerHTML: { __html: svg } } ),
					el( 'span', { lang: language }, name )
				),
				el( 'div', innerBlocksProps )
			);
		},

		save: function save() {
			return el( InnerBlocks.Content );
		},
	} );
} )( window.wp );
