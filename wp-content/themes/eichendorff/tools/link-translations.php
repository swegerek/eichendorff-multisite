<?php
/**
 * Links the Polish and German counterparts listed in tools/data/language-pairs.json.
 *
 * Local posts are found by their `_eichendorff_source` meta: `pl:` and `de:` for posts,
 * `pl-page:` and `de-page:` for pages. Pairs with a side not imported yet are skipped and
 * listed, so the script can run again after more content is imported.
 *
 * Run with: wp eval-file tools/link-translations.php --user=1
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_map = json_decode( file_get_contents( __DIR__ . '/data/language-pairs.json' ), true );

/**
 * Returns the local post imported from a production ID on a site, or 0.
 *
 * @param int    $site_id Site ID.
 * @param string $source  Source meta value, such as `de:535`.
 */
function eichendorff_link_find( int $site_id, string $source ): int {
	switch_to_blog( $site_id );
	$found = get_posts(
		array(
			'post_type'   => array( 'post', 'page' ),
			'post_status' => 'any',
			'meta_key'    => '_eichendorff_source',
			'meta_value'  => $source,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	restore_current_blog();

	return $found ? (int) $found[0] : 0;
}

$eichendorff_types = array(
	'pages' => array( 'pl-page', 'de-page' ),
	'posts' => array( 'pl', 'de' ),
);

switch_to_blog( 1 );

foreach ( $eichendorff_types as $eichendorff_type => $eichendorff_prefixes ) {
	$eichendorff_linked  = 0;
	$eichendorff_skipped = array();

	foreach ( $eichendorff_map[ $eichendorff_type ]['pairs'] as $eichendorff_pair ) {
		$eichendorff_pl = eichendorff_link_find( 1, "{$eichendorff_prefixes[0]}:{$eichendorff_pair['pl']}" );
		$eichendorff_de = eichendorff_link_find( 2, "{$eichendorff_prefixes[1]}:{$eichendorff_pair['de']}" );

		if ( ! $eichendorff_pl || ! $eichendorff_de ) {
			$eichendorff_skipped[] = sprintf( '%s ↔ %s (brak %s)', $eichendorff_pair['pl_slug'], $eichendorff_pair['de_slug'], $eichendorff_pl ? 'DE' : ( $eichendorff_de ? 'PL' : 'PL i DE' ) );
			continue;
		}

		eichendorff_set_translation( $eichendorff_pl, $eichendorff_de );
		++$eichendorff_linked;
	}

	WP_CLI::log( sprintf( '%s: powiązane %d, pominięte %d', $eichendorff_type, $eichendorff_linked, count( $eichendorff_skipped ) ) );

	foreach ( $eichendorff_skipped as $eichendorff_line ) {
		WP_CLI::log( "  pominięta: $eichendorff_line" );
	}
}

restore_current_blog();

WP_CLI::success( 'Powiązania zapisane.' );
