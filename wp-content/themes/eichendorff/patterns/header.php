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
?>
<!-- wp:group {"className":"site-header","backgroundColor":"bark","layout":{"type":"default"}} -->
<div class="wp-block-group site-header has-bark-background-color has-background">
	<!-- wp:group {"className":"site-header__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group site-header__inner">
		<!-- wp:site-title {"level":0,"className":"site-header__title","textColor":"sand","fontSize":"x-large","fontFamily":"inter"} /-->

		<!-- wp:navigation {"overlayMenu":"always","submenuVisibility":"click","className":"site-nav","layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:navigation-link {"label":"Home","url":"/","kind":"custom"} /-->
			<!-- wp:navigation-submenu {"label":"Fundacja","url":"#","kind":"custom"} -->
				<!-- wp:navigation-link {"label":"O nas","url":"/fundacja/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Statut fundacji","url":"/fundacja/statut-fundacji/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Zarząd","url":"/fundacja/zarzad/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Rys historyczny","url":"/fundacja/rys-historyczny/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Rada kuratorów","url":"/fundacja/rada-kuratorow/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Rada naukowa","url":"/fundacja/rada-naukowa/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Galeria zasłużonych","url":"/fundacja/galeria-zasluzonych/","kind":"custom"} /-->
			<!-- /wp:navigation-submenu -->
			<!-- wp:navigation-submenu {"label":"Hotel","url":"#","kind":"custom"} -->
				<!-- wp:navigation-link {"label":"Hotel i otoczenie","url":"/hotel/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Organizacja imprez","url":"/hotel/organizacja-imprez/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Cennik","url":"/hotel/hotel-cennik/","kind":"custom"} /-->
			<!-- /wp:navigation-submenu -->
			<!-- wp:navigation-link {"label":"Park Łubowice","url":"/park-lubowice/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Szlaki turystyczne","url":"/szlaki-turystyczne/","kind":"custom"} /-->
			<!-- wp:navigation-submenu {"label":"Publikacje","url":"#","kind":"custom"} -->
				<!-- wp:navigation-link {"label":"Publikacje – cennik","url":"/publikacje-cennik/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Cykle wydawnicze","url":"/cykle-wydawnicze/","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"Materiały zdigitalizowane","url":"/materialy-zdigitalizowane/","kind":"custom"} /-->
			<!-- /wp:navigation-submenu -->
			<!-- wp:navigation-link {"label":"Finansowanie","url":"/finansowanie/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Kontakt","url":"/kontakt/","kind":"custom"} /-->
		<!-- /wp:navigation -->

		<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"site-header__language"} -->
		<figure class="wp-block-image size-full site-header__language"><a href="https://eichendorff.pl/de/"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/de.png' ) ); ?>" alt="Deutsch" title="Gehen Sie zur deutschen Version"/></a></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
