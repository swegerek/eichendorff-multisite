<?php
/**
 * Link to the German site shown in the header.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the German counterpart of the current page.
 *
 * Temporary map of page paths, used until the language model is decided.
 */
function eichendorff_german_url(): string {
	$base = 'https://eichendorff.pl/de/';
	$map  = array(
		'fundacja'                     => 'stiftung/',
		'fundacja/statut-fundacji'     => 'stiftung/satzung/',
		'fundacja/zarzad'              => 'stiftung/stiftungsrat/',
		'fundacja/rys-historyczny'     => 'stiftung/geschichtlicher-riss/',
		'fundacja/rada-kuratorow'      => 'stiftung/kuratorenrat/',
		'fundacja/rada-naukowa'        => 'stiftung/wissenschaftsrat/',
		'fundacja/galeria-zasluzonych' => 'stiftung/verdiente-personlichkeiten/',
		'kontakt'                      => 'kontakt/',
		'filmy'                        => 'videos/',
		'hotel'                        => 'hotel-und-umgebung/',
		'hotel/organizacja-imprez'     => 'hotel-und-umgebung/organisation-von-veranstaltungen/',
		'hotel/hotel-cennik'           => 'hotel-und-umgebung/hotel-preisliste/',
		'publikacje-cennik'            => 'veroffentlichungen-preisliste/',
		'cykle-wydawnicze'             => 'veroffentlichungszyklen/',
		'materialy-zdigitalizowane'    => 'digitalisierte-verlage/',
		'finansowanie'                 => 'forderung/',
	);

	if ( is_page() ) {
		$path = get_page_uri( get_queried_object_id() );

		if ( isset( $map[ $path ] ) ) {
			return $base . $map[ $path ];
		}
	}

	return $base;
}

/**
 * Points the header flag at the German counterpart of the current page.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 */
function eichendorff_german_link( string $block_content, array $block ): string {
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'site-header__language' ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'href', eichendorff_german_url() );
		$tags->set_attribute( 'hreflang', 'de' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/image', 'eichendorff_german_link', 10, 2 );
