<?php
/**
 * Title: Nagłówek
 * Slug: eichendorff/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_flag = eichendorff_is_german()
	? array(
		'image' => 'assets/images/pl.png',
		'alt'   => 'Polski',
		'title' => 'Przełącz na język polski',
	)
	: array(
		'image' => 'assets/images/de.png',
		'alt'   => 'Deutsch',
		'title' => 'Gehen Sie zur deutschen Version',
	);
?>
<!-- wp:group {"className":"site-header","backgroundColor":"bark","layout":{"type":"default"}} -->
<div class="wp-block-group site-header has-bark-background-color has-background">
	<!-- wp:group {"className":"site-header__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group site-header__inner">
		<!-- wp:site-title {"level":0,"className":"site-header__title","textColor":"sand","fontSize":"x-large","fontFamily":"inter"} /-->

		<!-- wp:navigation {"overlayMenu":"always","submenuVisibility":"click","className":"site-nav","layout":{"type":"flex","justifyContent":"center"}} -->
<?php
			eichendorff_print_menu(
				eichendorff_is_german()
					? array(
						array( 'Home', '/' ),
						array(
							'Stiftung',
							null,
							array(
								array( 'Über uns', '/stiftung/' ),
								array( 'Satzung', '/stiftung/satzung/' ),
								array( 'Stiftungsrat', '/stiftung/stiftungsrat/' ),
								array( 'Geschichtlicher Riss', '/stiftung/geschichtlicher-riss/' ),
								array( 'Kuratorenrat', '/stiftung/kuratorenrat/' ),
								array( 'Wissenschaftsrat', '/stiftung/wissenschaftsrat/' ),
								array( 'Verdiente Persönlichkeiten', '/stiftung/verdiente-personlichkeiten/' ),
							),
						),
						array(
							'Hotel',
							null,
							array(
								array( 'Hotel und Umgebung', '/hotel-und-umgebung/' ),
								array( 'Organisation von Veranstaltungen', '/hotel-und-umgebung/organisation-von-veranstaltungen/' ),
								array( 'Preisliste', '/hotel-und-umgebung/hotel-preisliste/' ),
							),
						),
						array(
							'Veröffentlichungen',
							null,
							array(
								array( 'Veröffentlichungen – Preisliste', '/veroffentlichungen-preisliste/' ),
								array( 'Veröffentlichungszyklen', '/veroffentlichungszyklen/' ),
								array( 'Digitalisierte Verlage', '/digitalisierte-verlage/' ),
							),
						),
						array( 'Förderung', '/forderung/' ),
						array( 'Kontakt', '/kontakt/' ),
					)
					: array(
						array( 'Home', '/' ),
						array(
							'Fundacja',
							null,
							array(
								array( 'O nas', '/fundacja/' ),
								array( 'Statut fundacji', '/fundacja/statut-fundacji/' ),
								array( 'Zarząd', '/fundacja/zarzad/' ),
								array( 'Rys historyczny', '/fundacja/rys-historyczny/' ),
								array( 'Rada kuratorów', '/fundacja/rada-kuratorow/' ),
								array( 'Rada naukowa', '/fundacja/rada-naukowa/' ),
								array( 'Galeria zasłużonych', '/fundacja/galeria-zasluzonych/' ),
							),
						),
						array(
							'Hotel',
							null,
							array(
								array( 'Hotel i otoczenie', '/hotel/' ),
								array( 'Organizacja imprez', '/hotel/organizacja-imprez/' ),
								array( 'Cennik', '/hotel/hotel-cennik/' ),
							),
						),
						array( 'Park Łubowice', '/park-lubowice/' ),
						array( 'Szlaki turystyczne', '/szlaki-turystyczne/' ),
						array(
							'Publikacje',
							null,
							array(
								array( 'Publikacje – cennik', '/publikacje-cennik/' ),
								array( 'Cykle wydawnicze', '/cykle-wydawnicze/' ),
								array( 'Materiały zdigitalizowane', '/materialy-zdigitalizowane/' ),
							),
						),
						array( 'Finansowanie', '/finansowanie/' ),
						array( 'Kontakt', '/kontakt/' ),
					),
				3
			);
			?>
		<!-- /wp:navigation -->

		<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"site-header__language"} -->
		<figure class="wp-block-image size-full site-header__language"><a href="<?php echo esc_url( get_home_url( eichendorff_other_site(), '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( $eichendorff_flag['image'] ) ); ?>" alt="<?php echo esc_attr( $eichendorff_flag['alt'] ); ?>" title="<?php echo esc_attr( $eichendorff_flag['title'] ); ?>"/></a></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
