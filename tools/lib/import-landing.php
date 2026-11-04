<?php
/**
 * Import of the landing pages: Elementor sections become the page header, language sections,
 * cards, a two-column gallery and trail cards.
 *
 * Used by tools/import-pages.php.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

const EICHENDORFF_LANDING_PAGES = array( 'park-lubowice', 'szlaki-turystyczne' );

/**
 * Returns the first match of a pattern in the CSS rules of one Elementor element.
 *
 * @param string $css     Page CSS.
 * @param string $id      Elementor element ID.
 * @param string $pattern Rest of the pattern after the element class, with one capture group.
 */
function eichendorff_import_css_value( string $css, string $id, string $pattern ): string {
	return preg_match( '/elementor-element-' . preg_quote( $id, '/' ) . $pattern . '/i', $css, $m ) ? $m[1] : '';
}

/**
 * Returns the theme icon for an Elementor icon.
 *
 * @param DOMElement $svg  Icon SVG.
 * @param string     $page Page path, for error messages.
 */
function eichendorff_import_icon_name( DOMElement $svg, string $page ): string {
	$names = array(
		'lucide-trees'               => 'trees',
		'lucide-tree-deciduous'      => 'tree-deciduous',
		'lucide-footprints'          => 'footprints',
		'lucide-mountain'            => 'mountain',
		'e-fas-map-marker-alt'       => 'map-marker',
		'e-fas-info-circle'          => 'info',
		'e-fas-download'             => 'download',
		'e-fas-external-link-alt'    => 'external-link',
		'e-fas-long-arrow-alt-right' => 'arrow-right',
		'flag-icons-pl'              => 'flag-pl',
		'flag-icons-de'              => 'flag-de',
		'flag-icons-cz'              => 'flag-cz',
		'flag-icons-gb'              => 'flag-gb',
	);
	$label = $svg->getAttribute( 'class' ) . ' ' . $svg->getAttribute( 'id' );

	foreach ( $names as $source => $name ) {
		if ( in_array( $source, explode( ' ', $label ), true ) ) {
			return "eichendorff/$name";
		}
	}

	// Material Symbols bike, the only icon in that viewBox (the HTML parser lowercases the attribute).
	if ( '0 -960 960 960' === $svg->getAttribute( 'viewbox' ) ) {
		return 'eichendorff/bike';
	}

	throw new RuntimeException( "$page: ikona `$label` nie ma odpowiednika w motywie." );
}

/**
 * Returns the element children of a node.
 *
 * @param DOMElement $node Node.
 */
function eichendorff_import_children( DOMElement $node ): array {
	return array_values( array_filter( iterator_to_array( $node->childNodes ), static fn( $child ): bool => $child instanceof DOMElement ) );
}

/**
 * Serializes a block without inner content.
 *
 * @param string $name  Block name.
 * @param array  $attrs Block attributes.
 */
function eichendorff_import_void_block( string $name, array $attrs ): string {
	return "<!-- wp:$name " . serialize_block_attributes( $attrs ) . ' /-->';
}

/**
 * Turns a link to production into a local one; files from production go to the media library.
 *
 * @param string $url  Link from production.
 * @param string $page Page path, for error messages.
 */
function eichendorff_import_landing_link( string $url, string $page ): string {
	if ( ! str_starts_with( $url, EICHENDORFF_SOURCE_SITE . '/' ) ) {
		return $url;
	}

	if ( str_contains( $url, '/wp-content/uploads/' ) ) {
		$id = eichendorff_media_from_url( $url, 0 );

		if ( ! $id ) {
			throw new RuntimeException( "$page: nie udało się pobrać pliku $url." );
		}

		return wp_get_attachment_url( $id );
	}

	return home_url( substr( $url, strlen( EICHENDORFF_SOURCE_SITE ) ) );
}

/**
 * Turns an image widget into an Image block, with the lightbox when production opened one.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_image( DOMElement $widget, string $page ): string {
	$xpath = new DOMXPath( $widget->ownerDocument );
	$img   = $xpath->query( './/img', $widget )->item( 0 );
	$link  = $xpath->query( './/a[@data-elementor-open-lightbox="yes"]', $widget )->item( 0 );
	$full  = $link ? $link->getAttribute( 'href' ) : preg_replace( '/-\d+x\d+(\.\w+)$/', '$1', $img->getAttribute( 'src' ) );
	$id    = eichendorff_media_from_url( $full, 0 );

	if ( ! $id ) {
		throw new RuntimeException( "$page: nie udało się pobrać zdjęcia $full." );
	}

	$size  = preg_match( '/\bsize-([\w-]+)/', $img->getAttribute( 'class' ), $m ) ? $m[1] : 'large';
	$attrs = array(
		'id'              => $id,
		'sizeSlug'        => $size,
		'linkDestination' => 'none',
	);

	if ( $link ) {
		$attrs = array( 'lightbox' => array( 'enabled' => true ) ) + $attrs;
	}

	$src = esc_url( wp_get_attachment_image_url( $id, $size ) );

	return eichendorff_import_block( 'image', $attrs, "<figure class=\"wp-block-image size-$size\"><img src=\"$src\" alt=\"\" class=\"wp-image-$id\"/></figure>" );
}

/**
 * Turns a container of button widgets into a Buttons block with the Download, External link or Arrow style.
 *
 * @param DOMElement $container Container of buttons.
 * @param string     $page      Page path, for error messages.
 */
function eichendorff_import_buttons( DOMElement $container, string $page ): string {
	$styles  = array(
		'eichendorff/download'      => 'is-style-download',
		'eichendorff/external-link' => 'is-style-external',
		'eichendorff/arrow-right'   => 'is-style-arrow',
	);
	$xpath   = new DOMXPath( $container->ownerDocument );
	$buttons = array();

	foreach ( $xpath->query( './/a[contains(@class, "elementor-button")]', $container ) as $link ) {
		$style     = $styles[ eichendorff_import_icon_name( $xpath->query( './/svg', $link )->item( 0 ), $page ) ] ?? '';
		$url       = esc_url( eichendorff_import_landing_link( $link->getAttribute( 'href' ), $page ) );
		$text      = esc_html( trim( $xpath->query( './/*[contains(@class, "elementor-button-text")]', $link )->item( 0 )->textContent ) );
		$buttons[] = eichendorff_import_block( 'button', array( 'className' => $style ), "<div class=\"wp-block-button $style\"><a class=\"wp-block-button__link wp-element-button\" href=\"$url\">$text</a></div>" );
	}

	return eichendorff_import_block( 'buttons', array( 'layout' => array( 'type' => 'flex', 'justifyContent' => 'center' ) ), '<div class="wp-block-buttons">' . implode( "\n\n", $buttons ) . '</div>' );
}

/**
 * Turns a one-item icon list into a centred row with the icon and the text.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_icon_row( DOMElement $widget, string $page ): string {
	$xpath = new DOMXPath( $widget->ownerDocument );
	$icon  = eichendorff_import_void_block( 'icon', array( 'icon' => eichendorff_import_icon_name( $xpath->query( './/svg', $widget )->item( 0 ), $page ) ) );
	$text  = eichendorff_import_paragraph( esc_html( trim( $xpath->query( './/*[contains(@class, "elementor-icon-list-text")]', $widget )->item( 0 )->textContent ) ) );

	return eichendorff_import_block(
		'group',
		array(
			'className' => 'icon-row',
			'layout'    => array(
				'type'           => 'flex',
				'flexWrap'       => 'nowrap',
				'justifyContent' => 'center',
			),
		),
		"<div class=\"wp-block-group icon-row\">$icon\n\n$text</div>"
	);
}

/**
 * Turns the background container of the first section into a Cover with the Page header style.
 *
 * @param DOMElement $container Container with the background.
 * @param string     $css       Page CSS.
 * @param string     $page      Page path, for error messages.
 */
function eichendorff_import_hero( DOMElement $container, string $css, string $page ): string {
	$id     = eichendorff_import_element_id( $container );
	$inner  = implode( "\n\n", eichendorff_import_widgets( $container, $page ) );
	$image  = eichendorff_import_css_value( $css, $id, '[^{]*\{[^}]*background-image:url\("([^"]+)"\)' );
	$height = (int) eichendorff_import_css_value( $css, $id, '\{[^}]*--min-height:(\d+)px' );

	if ( ! $image ) {
		$attrs = array(
			'dimRatio'           => 20,
			'overlayColor'       => 'sand',
			'isUserOverlayColor' => true,
			'isDark'             => false,
			'className'          => 'is-style-hero',
		);

		return eichendorff_import_block( 'cover', $attrs, "<div class=\"wp-block-cover is-light is-style-hero\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-sand-background-color has-background-dim-20 has-background-dim\"></span><div class=\"wp-block-cover__inner-container\">$inner</div></div>" );
	}

	$media = eichendorff_media_from_url( preg_replace( '/-\d+x\d+(\.\w+)$/', '$1', $image ), 0 );

	if ( ! $media ) {
		throw new RuntimeException( "$page: nie udało się pobrać tła nagłówka $image." );
	}

	$src   = esc_url( wp_get_attachment_image_url( $media, '1536x1536' ) );
	$attrs = array(
		'url'                => $src,
		'id'                 => $media,
		'dimRatio'           => 10,
		'overlayColor'       => 'white',
		'isUserOverlayColor' => true,
		'minHeight'          => $height ? $height : 400,
		'minHeightUnit'      => 'px',
		'isDark'             => false,
		'sizeSlug'           => '1536x1536',
		'className'          => 'is-style-hero',
	);
	$style = 'min-height:' . $attrs['minHeight'] . 'px';

	return eichendorff_import_block( 'cover', $attrs, "<div class=\"wp-block-cover is-light is-style-hero\" style=\"$style\"><img class=\"wp-block-cover__image-background wp-image-$media size-1536x1536\" alt=\"\" src=\"$src\" data-object-fit=\"cover\"/><span aria-hidden=\"true\" class=\"wp-block-cover__background has-white-background-color has-background-dim-10 has-background-dim\"></span><div class=\"wp-block-cover__inner-container\">$inner</div></div>" );
}

/**
 * Turns a container with a flag, a divider and a text into a Language section block.
 *
 * @param DOMElement $container Container of one language.
 * @param string     $page      Page path, for error messages.
 */
function eichendorff_import_language_section( DOMElement $container, string $page ): string {
	$languages = array(
		'eichendorff/flag-pl' => 'pl',
		'eichendorff/flag-de' => 'de',
		'eichendorff/flag-cz' => 'cs',
		'eichendorff/flag-gb' => 'en',
	);
	$xpath     = new DOMXPath( $container->ownerDocument );
	$language  = $languages[ eichendorff_import_icon_name( $xpath->query( './/svg', $container )->item( 0 ), $page ) ] ?? '';

	if ( ! $language ) {
		throw new RuntimeException( "$page: sekcja językowa bez flagi." );
	}

	$text = array();

	foreach ( $xpath->query( './/*[@data-widget_type="text-editor.default"]', $container ) as $widget ) {
		array_push( $text, ...eichendorff_import_text_editor( $widget, $page ) );
	}

	$attrs = 'pl' === $language ? '' : ' ' . serialize_block_attributes( array( 'language' => $language ) );

	return "<!-- wp:eichendorff/language-section$attrs -->\n" . implode( "\n\n", $text ) . "\n<!-- /wp:eichendorff/language-section -->";
}

/**
 * Turns a trail container into a Trail card block.
 *
 * @param DOMElement $container Container of one trail.
 * @param string     $css       Page CSS.
 * @param string     $page      Page path, for error messages.
 */
function eichendorff_import_trail_card( DOMElement $container, string $css, string $page ): string {
	$xpath  = new DOMXPath( $container->ownerDocument );
	$icon   = $xpath->query( './/*[contains(@class, "trail-icon")]', $container )->item( 0 );
	$marker = $xpath->query( './/*[contains(@class, "trail-marker")]', $container )->item( 0 );
	$title  = $xpath->query( './/h3', $container )->item( 0 );

	if ( ! $icon || ! $marker || ! $title ) {
		throw new RuntimeException( "$page: karta szlaku bez ikony, oznaczenia albo nazwy." );
	}

	$icon_id   = eichendorff_import_element_id( $icon );
	$marker_id = eichendorff_import_element_id( $marker );
	$named     = array( 'green' => '#008000' );
	$color     = eichendorff_import_css_value( $css, $icon_id, ' svg\s*\{\s*stroke:\s*([#\w]+)' );
	$color     = $color ? $color : eichendorff_import_css_value( $css, $icon_id, '[^{]*\.elementor-icon svg\{fill:(#[0-9a-f]{6})(?!00)' );
	$texts     = array_map( static fn( DOMNode $node ): string => trim( $node->textContent ), iterator_to_array( $xpath->query( './/p[contains(@class, "elementor-heading-title")]', $container ) ) );
	$after     = static fn( string $label ): string => $texts[ array_search( $label, $texts, true ) + 1 ] ?? '';
	$attrs     = array(
		'title'          => esc_html( trim( $title->textContent ) ),
		'route'          => esc_html( $after( 'Trasa' ) ),
		'distance'       => esc_html( $after( 'Dystans' ) ),
		'icon'           => eichendorff_import_icon_name( $xpath->query( './/svg', $icon )->item( 0 ), $page ),
		'iconColor'      => strtoupper( $named[ $color ] ?? $color ),
		'iconBackground' => strtoupper(
			eichendorff_import_css_value( $css, $icon_id, '\s*\{\s*background:\s*(#[0-9a-f]{3,8})\s*!important' )
			?: eichendorff_import_css_value( $css, $icon_id, '\{background-color:(#[0-9a-f]{3,8})' )
		),
		'markerColor'    => strtoupper( eichendorff_import_css_value( $css, $marker_id, '\{--divider-border-style:solid;--divider-color:(#[0-9a-f]{3,8})' ) ),
	);

	if ( preg_match( '/elementor-element-' . $marker_id . '\s*\{\s*--trail-marker-width:\s*3rem/', $css ) ) {
		$attrs['markerStyle'] = 'long';
	}

	foreach ( $xpath->query( './/a[contains(@class, "elementor-button")]', $container ) as $link ) {
		$type = strtolower( pathinfo( (string) wp_parse_url( $link->getAttribute( 'href' ), PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		$id   = in_array( $type, array( 'gpx', 'kml' ), true ) ? eichendorff_media_from_url( $link->getAttribute( 'href' ), 0 ) : 0;

		if ( ! $id ) {
			throw new RuntimeException( "$page: nie udało się pobrać trasy {$link->getAttribute( 'href' )}." );
		}

		$attrs[ "{$type}Id" ]  = $id;
		$attrs[ "{$type}Url" ] = wp_get_attachment_url( $id );
	}

	foreach ( array( 'route', 'distance', 'iconColor', 'iconBackground', 'markerColor' ) as $key ) {
		if ( '' === $attrs[ $key ] ) {
			throw new RuntimeException( "$page: karta szlaku `{$attrs['title']}` bez wartości `$key`." );
		}
	}

	return eichendorff_import_void_block( 'eichendorff/trail-card', $attrs );
}

/**
 * Turns a grid of image widgets into a two-column Gallery of 4:3 crops with the lightbox.
 *
 * @param array  $widgets Image widgets.
 * @param string $page    Page path, for error messages.
 */
function eichendorff_import_two_column_gallery( array $widgets, string $page ): string {
	$images = array();

	foreach ( $widgets as $widget ) {
		$link = ( new DOMXPath( $widget->ownerDocument ) )->query( './/a', $widget )->item( 0 );
		$id   = $link ? eichendorff_media_from_url( $link->getAttribute( 'href' ), 0, $link->getAttribute( 'data-elementor-lightbox-title' ) ) : 0;

		if ( ! $id ) {
			throw new RuntimeException( "$page: zdjęcie galerii bez pliku." );
		}

		$attrs    = array(
			'lightbox'        => array( 'enabled' => true ),
			'id'              => $id,
			'sizeSlug'        => 'medium_large',
			'linkDestination' => 'none',
		);
		$src      = esc_url( wp_get_attachment_image_url( $id, 'medium_large' ) );
		$images[] = eichendorff_import_block( 'image', $attrs, "<figure class=\"wp-block-image size-medium_large\"><img src=\"$src\" alt=\"\" class=\"wp-image-$id\"/></figure>" );
	}

	return eichendorff_import_block(
		'gallery',
		array(
			'linkTo'    => 'none',
			'sizeSlug'  => 'medium_large',
			'className' => 'is-style-two-columns',
		),
		'<figure class="wp-block-gallery has-nested-images columns-default is-cropped is-style-two-columns">' . implode( "\n\n", $images ) . '</figure>'
	);
}

/**
 * Builds the blocks of a landing page from its Elementor sections.
 *
 * The first section is the page header; a grid of images is a gallery, a grid of containers holds
 * language sections; a section with trail icons is the trail list; any other section holds cards.
 *
 * @param DOMElement $root   Wrapper of the rendered page.
 * @param array      $source Production page.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_landing( DOMElement $root, array $source, string $page ): array {
	$css      = eichendorff_import_page_css( $source['id'] );
	$xpath    = new DOMXPath( $root->ownerDocument );
	$sections = iterator_to_array( $xpath->query( './/*[contains(concat(" ", @class, " "), " e-parent ")]', $root ) );
	$blocks   = array();

	foreach ( $sections as $index => $section ) {
		$inner    = $xpath->query( './*[contains(@class, "e-con-inner")]', $section )->item( 0 ) ?? $section;
		$children = eichendorff_import_children( $inner );
		$is_grid  = in_array( 'e-grid', explode( ' ', $section->getAttribute( 'class' ) ), true );

		if ( 0 === $index ) {
			$blocks[] = eichendorff_import_hero( $children[0], $css, $page );
		} elseif ( $is_grid && array_filter( $children, static fn( DOMElement $child ): bool => 'image.default' === $child->getAttribute( 'data-widget_type' ) ) === $children ) {
			$blocks[] = eichendorff_import_two_column_gallery( $children, $page );
		} elseif ( $is_grid ) {
			$languages = array_map( static fn( DOMElement $child ): string => eichendorff_import_language_section( $child, $page ), $children );
			$blocks[]  = eichendorff_import_block( 'group', array( 'className' => 'is-style-two-columns', 'layout' => array( 'type' => 'default' ) ), '<div class="wp-block-group is-style-two-columns">' . implode( "\n\n", $languages ) . '</div>' );
		} elseif ( $xpath->query( './/*[contains(@class, "trail-icon")]', $section )->length ) {
			$cards    = array_map( static fn( DOMElement $child ): string => eichendorff_import_trail_card( $child, $css, $page ), $children );
			$blocks[] = eichendorff_import_block( 'eichendorff/trail-list', array(), '<div class="wp-block-eichendorff-trail-list">' . implode( "\n\n", $cards ) . '</div>' );
		} else {
			foreach ( $children as $card ) {
				$blocks[] = eichendorff_import_block( 'group', array( 'className' => 'is-style-card', 'layout' => array( 'type' => 'default' ) ), '<div class="wp-block-group is-style-card">' . implode( "\n\n", eichendorff_import_widgets( $card, $page ) ) . '</div>' );
			}
		}
	}

	return $blocks;
}
