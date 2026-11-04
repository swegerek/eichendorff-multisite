<?php
/**
 * Creates the events shown on the production home page.
 *
 * Run with: wp eval-file tools/seed-events.php --user=1
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_events = array(
	array(
		'Dzień św. Marcina w Łubowicach',
		'2026-11-08',
		'Nabożeństwo i tradycyjna procesja z lampionami za św. Marcinem na koniu, zakończona słodkim poczęstunkiem. Niedziela, 8 listopada 2026 r., godz. 15:30. Początek w kościele parafialnym w Łubowicach.',
	),
	array(
		'„Z życia nicponia” - widowisko teatralne',
		'2026-08-23',
		'Spektakl na motywach noweli Josepha von Eichendorffa z muzyką na żywo, tańcem i choreografią. Park przy ruinach zamku Eichendorffów w Łubowicach, niedziela, 23 sierpnia 2026 r., godz. 17:00. Wstęp wolny.',
	),
	array(
		'238. rocznica urodzin poety J. von Eichendorffa',
		'2026-03-15',
		'Uroczystości w Łubowicach: msza i procesja oraz akademia z częścią artystyczną. Niedziela, 15 marca, godz. 15:00.',
	),
);

foreach ( $eichendorff_events as [ $eichendorff_title, $eichendorff_date, $eichendorff_text ] ) {
	$eichendorff_found = get_posts(
		array(
			'post_type'   => 'calendar_event',
			'post_status' => 'any',
			'title'       => $eichendorff_title,
			'fields'      => 'ids',
		)
	);

	if ( $eichendorff_found ) {
		continue;
	}

	wp_insert_post(
		array(
			'post_type'    => 'calendar_event',
			'post_status'  => 'publish',
			'post_title'   => $eichendorff_title,
			'post_excerpt' => $eichendorff_text,
			'meta_input'   => array( 'event_date' => $eichendorff_date ),
		)
	);
}

WP_CLI::success( 'Wydarzenia gotowe.' );
