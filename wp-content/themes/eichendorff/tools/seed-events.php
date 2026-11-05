<?php
/**
 * Creates the events shown on the production home page, in the language of the current site.
 *
 * Run with: wp eval-file tools/seed-events.php --user=1
 *      and: wp eval-file tools/seed-events.php --url=<host>/de/ --user=1
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_events = array(
	'pl' => array(
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
	),
	'de' => array(
		array(
			'Martinstag in Lubowitz',
			'2026-11-08',
			'Andacht und traditioneller Laternenumzug mit St. Martin hoch zu Ross, anschließend süße Bewirtung. Sonntag, 8. November 2026, um 15:30 Uhr. Beginn in der Pfarrkirche in Lubowitz.',
		),
		array(
			'„Aus dem Leben eines Taugenichts“ - Theateraufführung',
			'2026-08-23',
			'Theaterstück nach der Novelle von Joseph von Eichendorff mit Live-Musik, Tanz und Choreografie. Park bei den Ruinen der Burg der Familie Eichendorff in Lubowitz, Sonntag, 23. August 2026, um 17:00 Uhr. Eintritt frei.',
		),
		array(
			'238. Geburtstag des Dichters Joseph von Eichendorff',
			'2026-03-15',
			'Feierlichkeiten in Lubowitz: Heilige Messe und Prozession, danach Feierstunde mit musikalischem Programm. Sonntag, 15. März, 15:00 Uhr.',
		),
	),
);

foreach ( $eichendorff_events[ eichendorff_is_german() ? 'de' : 'pl' ] as [ $eichendorff_title, $eichendorff_date, $eichendorff_text ] ) {
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
