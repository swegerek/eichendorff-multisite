<?php
/**
 * Landing pages: theme icons, block styles, route files and the blocks of the tourist pages.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme icons, by name in the `eichendorff` collection.
 *
 * Taken from production: flags from flag-icons (MIT), outline icons from Lucide (ISC, `is-stroke`),
 * the bike from Material Symbols (Apache 2.0), the rest from Font Awesome Free (CC BY 4.0).
 */
function eichendorff_icons(): array {
	return array(
		'flag-pl'        => __( 'Flaga Polski', 'eichendorff' ),
		'flag-de'        => __( 'Flaga Niemiec', 'eichendorff' ),
		'flag-cz'        => __( 'Flaga Czech', 'eichendorff' ),
		'flag-gb'        => __( 'Flaga Wielkiej Brytanii', 'eichendorff' ),
		'trees'          => __( 'Drzewa', 'eichendorff' ),
		'tree-deciduous' => __( 'Drzewo', 'eichendorff' ),
		'bike'           => __( 'Rower', 'eichendorff' ),
		'footprints'     => __( 'Ślady stóp', 'eichendorff' ),
		'mountain'       => __( 'Góry', 'eichendorff' ),
		'map-marker'     => __( 'Pinezka', 'eichendorff' ),
		'download'       => __( 'Pobieranie', 'eichendorff' ),
		'external-link'  => __( 'Link zewnętrzny', 'eichendorff' ),
		'arrow-right'    => __( 'Strzałka w prawo', 'eichendorff' ),
		'info'           => __( 'Informacja', 'eichendorff' ),
	);
}

/**
 * Registers the theme icons for the Icon block and the trail cards.
 */
function eichendorff_register_icons(): void {
	if ( ! function_exists( 'wp_register_icon_collection' ) ) {
		return;
	}

	wp_register_icon_collection( 'eichendorff', array( 'label' => __( 'Eichendorff', 'eichendorff' ) ) );

	foreach ( eichendorff_icons() as $name => $label ) {
		wp_register_icon(
			"eichendorff/$name",
			array(
				'label'     => $label,
				'file_path' => get_theme_file_path( "assets/icons/$name.svg" ),
			)
		);
	}
}
add_action( 'init', 'eichendorff_register_icons' );

/**
 * Registers the block styles of the landing pages.
 */
function eichendorff_register_landing_styles(): void {
	$styles = array(
		'core/cover'   => array( 'hero' => __( 'Nagłówek strony', 'eichendorff' ) ),
		'core/group'   => array(
			'card'        => __( 'Karta', 'eichendorff' ),
			'two-columns' => __( 'Dwie kolumny', 'eichendorff' ),
		),
		'core/gallery' => array( 'two-columns' => __( 'Dwie kolumny', 'eichendorff' ) ),
		'core/button'  => array(
			'download' => __( 'Pobierz', 'eichendorff' ),
			'external' => __( 'Link zewnętrzny', 'eichendorff' ),
			'arrow'    => __( 'Strzałka', 'eichendorff' ),
		),
	);

	foreach ( $styles as $block => $block_styles ) {
		foreach ( $block_styles as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'eichendorff_register_landing_styles' );

/**
 * Lets editors upload route files.
 *
 * @param array $mimes Allowed types by extension.
 */
function eichendorff_route_mimes( array $mimes ): array {
	$mimes['gpx'] = 'application/gpx+xml';
	$mimes['kml'] = 'application/vnd.google-earth.kml+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'eichendorff_route_mimes' );

/**
 * Accepts GPX and KML files, which the file type check sees as XML or plain text.
 *
 * @param array  $data     Checked extension and type.
 * @param string $file     Full path to the file.
 * @param string $filename File name.
 * @param array  $mimes    Allowed types.
 */
function eichendorff_route_filetype( array $data, string $file, string $filename, $mimes ): array {
	if ( $data['ext'] ) {
		return $data;
	}

	$extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	$allowed   = eichendorff_route_mimes( array() );

	if ( isset( $allowed[ $extension ] ) && ( ! is_array( $mimes ) || isset( $mimes[ $extension ] ) ) ) {
		$real = function_exists( 'finfo_open' ) ? (string) finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $file ) : '';

		if ( in_array( $real, array( 'text/xml', 'application/xml', 'text/plain', 'application/gpx+xml', 'application/vnd.google-earth.kml+xml' ), true ) ) {
			$data['ext']  = $extension;
			$data['type'] = $allowed[ $extension ];
		}
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'eichendorff_route_filetype', 10, 4 );

/**
 * Makes a button with the Download style save its file instead of opening it.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 */
function eichendorff_download_button( string $block_content, array $block ): string {
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'is-style-download' ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'download', true );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/button', 'eichendorff_download_button', 10, 2 );

/**
 * Styles the icon pickers outside the editor canvas: outline icons as outlines, the trail card icon grid.
 */
function eichendorff_editor_icon_styles(): void {
	wp_add_inline_style(
		'wp-block-editor',
		'svg.is-stroke{fill:none!important;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}'
		. '.eichendorff-trail-popover{display:grid;gap:16px;width:264px;padding:16px}'
		. '.eichendorff-icon-grid{display:grid;grid-template-columns:repeat(6,36px);gap:4px}'
		. '.eichendorff-icon-grid .components-button{justify-content:center;width:36px;height:36px;padding:0}'
		. '.eichendorff-icon-preview svg{display:block;width:24px;height:24px;fill:currentColor}'
	);
}
add_action( 'enqueue_block_editor_assets', 'eichendorff_editor_icon_styles' );
