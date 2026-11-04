// Trail card: name, route and distance are typed in the card. Clicking the icon or the marking opens its
// settings; the same settings are in the block toolbar and the sidebar, the route files in the card's buttons.
( function ( wp ) {
	const { createElement: el, Fragment, useEffect, useState } = wp.element;
	const { useBlockProps, RichText, InspectorControls, BlockControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
	const { PanelBody, Button, Dropdown, ToolbarGroup, ToolbarButton, ColorPalette, BaseControl, ButtonGroup } = wp.components;

	const trailIcons = [
		[ 'eichendorff/tree-deciduous', 'Drzewo' ],
		[ 'eichendorff/trees', 'Drzewa' ],
		[ 'eichendorff/bike', 'Rower' ],
		[ 'eichendorff/footprints', 'Ślady stóp' ],
		[ 'eichendorff/mountain', 'Góry' ],
		[ 'eichendorff/map-marker', 'Pinezka' ],
	];

	// Trail colours used on the site, then the theme colours.
	const trailColors = [
		{ name: 'Zielony', color: '#0E9927' },
		{ name: 'Ciemnozielony', color: '#008000' },
		{ name: 'Jasnozielony', color: '#3ADF42' },
		{ name: 'Niebieski', color: '#1577C0' },
		{ name: 'Jasnoniebieski', color: '#527DDD' },
		{ name: 'Brązowy', color: '#9C6917' },
		{ name: 'Fioletowy', color: '#C553D6' },
		{ name: 'Brąz serwisu', color: '#653724' },
	];

	const files = [
		[ 'gpx', 'Format GPX', 'application/gpx+xml' ],
		[ 'kml', 'Format KML', 'application/vnd.google-earth.kml+xml' ],
	];

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

	const cls = ( part ) => `wp-block-eichendorff-trail-card__${ part }`;

	const Svg = ( { name } ) => el( 'span', { className: 'eichendorff-icon-preview', dangerouslySetInnerHTML: { __html: useIcon( name ) } } );

	// A background 8% as strong as the icon colour, as on the trails page.
	const lightBackground = ( color ) => ( /^#[0-9a-f]{6}$/i.test( color ) ? `${ color }15` : '#C8B9A040' );

	function IconSettings( { attributes, setAttributes } ) {
		return el(
			Fragment,
			null,
			el(
				BaseControl,
				{ label: 'Ikona', id: 'eichendorff-trail-icon', __nextHasNoMarginBottom: true },
				el(
					'div',
					{ className: 'eichendorff-icon-grid' },
					trailIcons.map( ( [ name, label ] ) =>
						el(
							Button,
							{
								key: name,
								label,
								showTooltip: true,
								isPressed: attributes.icon === name,
								onClick: () => setAttributes( { icon: name } ),
							},
							el( Svg, { name } )
						)
					)
				)
			),
			el(
				BaseControl,
				{ label: 'Kolor ikony', id: 'eichendorff-trail-icon-color', __nextHasNoMarginBottom: true },
				el( ColorPalette, {
					colors: trailColors,
					value: attributes.iconColor,
					clearable: false,
					onChange: ( value ) => value && setAttributes( { iconColor: value, iconBackground: lightBackground( value ) } ),
				} )
			),
			el(
				BaseControl,
				{ label: 'Tło ikony', help: 'Ustawia się samo przy zmianie koloru ikony.', id: 'eichendorff-trail-icon-background', __nextHasNoMarginBottom: true },
				el( ColorPalette, {
					colors: [ { name: 'Jasne tło koloru ikony', color: lightBackground( attributes.iconColor ) }, { name: 'Piaskowy', color: '#C8B9A040' }, ...trailColors ],
					value: attributes.iconBackground,
					enableAlpha: true,
					clearable: false,
					onChange: ( value ) => value && setAttributes( { iconBackground: value } ),
				} )
			)
		);
	}

	function MarkerSettings( { attributes, setAttributes } ) {
		return el(
			Fragment,
			null,
			el(
				BaseControl,
				{ label: 'Kolor oznaczenia', id: 'eichendorff-trail-marker-color', __nextHasNoMarginBottom: true },
				el( ColorPalette, {
					colors: [ { name: 'Jak ikona', color: attributes.iconColor }, ...trailColors.filter( ( c ) => c.color !== attributes.iconColor ) ],
					value: attributes.markerColor,
					clearable: false,
					onChange: ( value ) => value && setAttributes( { markerColor: value } ),
				} )
			),
			el(
				BaseControl,
				{ label: 'Kreski', id: 'eichendorff-trail-marker-style', __nextHasNoMarginBottom: true },
				el(
					ButtonGroup,
					null,
					[
						[ 'short', 'Krótkie' ],
						[ 'long', 'Długie' ],
					].map( ( [ value, label ] ) =>
						el(
							Button,
							{ key: value, variant: attributes.markerStyle === value ? 'primary' : 'secondary', onClick: () => setAttributes( { markerStyle: value } ) },
							label
						)
					)
				)
			)
		);
	}

	// Opens the settings in a popover from any toggle: the icon, the marking or a toolbar button.
	const withPopover = ( Settings, props, renderToggle ) =>
		el( Dropdown, {
			popoverProps: { placement: 'bottom-start' },
			renderToggle,
			renderContent: () => el( 'div', { className: 'eichendorff-trail-popover' }, el( Settings, props ) ),
		} );

	wp.blocks.registerBlockType( 'eichendorff/trail-card', {
		edit: function Edit( props ) {
			const { attributes, setAttributes } = props;
			const svg = useIcon( attributes.icon );
			const blockProps = useBlockProps( {
				className: 'long' === attributes.markerStyle ? 'is-marker-long' : '',
				style: {
					'--trail-icon-color': attributes.iconColor,
					'--trail-icon-background': attributes.iconBackground,
					'--trail-marker-color': attributes.markerColor,
				},
			} );
			const text = ( key, tagName, className, placeholder ) =>
				el( RichText, {
					tagName,
					className: cls( className ),
					value: attributes[ key ],
					placeholder,
					allowedFormats: [ 'core/bold', 'core/italic' ],
					onChange: ( value ) => setAttributes( { [ key ]: value } ),
				} );

			const filePicker = ( [ type, label, mime ] ) =>
				el(
					MediaUploadCheck,
					{ key: type },
					el( MediaUpload, {
						allowedTypes: [ mime ],
						value: attributes[ `${ type }Id` ],
						onSelect: ( media ) => setAttributes( { [ `${ type }Id` ]: media.id, [ `${ type }Url` ]: media.url } ),
						render: ( { open } ) =>
							el(
								'div',
								{ className: 'wp-block-button is-style-download' },
								el(
									'button',
									{ type: 'button', className: 'wp-block-button__link wp-element-button', onClick: open },
									attributes[ `${ type }Url` ] ? label : `Wybierz plik ${ type.toUpperCase() }`
								)
							),
					} )
				);

			const toolbarToggle = ( icon, label ) => ( { isOpen, onToggle } ) =>
				el( ToolbarButton, { icon, label, onClick: onToggle, 'aria-expanded': isOpen, isPressed: isOpen } );

			return el(
				'article',
				blockProps,
				el(
					BlockControls,
					{ group: 'block' },
					el(
						ToolbarGroup,
						null,
						withPopover( IconSettings, props, toolbarToggle( 'admin-appearance', 'Ikona i kolory' ) ),
						withPopover( MarkerSettings, props, toolbarToggle( 'minus', 'Oznaczenie szlaku' ) )
					)
				),
				el(
					InspectorControls,
					null,
					el( PanelBody, { title: 'Ikona' }, el( IconSettings, props ) ),
					el( PanelBody, { title: 'Oznaczenie szlaku' }, el( MarkerSettings, props ) ),
					el(
						PanelBody,
						{ title: 'Pliki trasy', initialOpen: false },
						files.map( ( [ type, label ] ) =>
							attributes[ `${ type }Url` ]
								? el(
										Button,
										{
											key: type,
											variant: 'link',
											isDestructive: true,
											onClick: () => setAttributes( { [ `${ type }Id` ]: undefined, [ `${ type }Url` ]: '' } ),
										},
										`Usuń plik: ${ label }`
								  )
								: el( 'p', { key: type }, `${ label }: brak pliku` )
						)
					)
				),
				el(
					'div',
					{ className: cls( 'info' ) },
					el(
						'div',
						{ className: cls( 'title' ) },
						withPopover( IconSettings, props, ( { isOpen, onToggle } ) =>
							el( 'button', {
								type: 'button',
								className: `${ cls( 'icon' ) } is-editable`,
								'aria-label': 'Zmień ikonę i kolory',
								title: 'Zmień ikonę i kolory',
								'aria-expanded': isOpen,
								onClick: onToggle,
								dangerouslySetInnerHTML: { __html: svg },
							} )
						),
						text( 'title', 'h3', 'name', 'Nazwa szlaku' )
					),
					el( 'div', { className: cls( 'fact' ) }, el( 'p', { className: cls( 'label' ) }, 'Trasa' ), text( 'route', 'p', 'value', 'Skąd – dokąd' ) ),
					el( 'div', { className: cls( 'fact' ) }, el( 'p', { className: cls( 'label' ) }, 'Dystans' ), text( 'distance', 'p', 'value', 'np. 5 km' ) ),
					el(
						'div',
						{ className: `${ cls( 'fact' ) } ${ cls( 'fact--marker' ) }` },
						el( 'p', { className: cls( 'label' ) }, 'Oznaczenie szlaku' ),
						withPopover( MarkerSettings, props, ( { isOpen, onToggle } ) =>
							el(
								'button',
								{
									type: 'button',
									className: `${ cls( 'marker-toggle' ) } is-editable`,
									'aria-label': 'Zmień oznaczenie szlaku',
									title: 'Zmień oznaczenie szlaku',
									'aria-expanded': isOpen,
									onClick: onToggle,
								},
								el( 'span', { className: cls( 'marker' ) } )
							)
						)
					)
				),
				el( 'div', { className: cls( 'download' ) }, el( 'p', { className: cls( 'label' ) }, 'Pobierz trasę' ), files.map( filePicker ) )
			);
		},

		save: () => null,
	} );
} )( window.wp );
