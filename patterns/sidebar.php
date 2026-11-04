<?php
/**
 * Title: Panel boczny
 * Slug: eichendorff/sidebar
 * Inserter: no
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"sidebar-section is-events","backgroundColor":"sand","layout":{"type":"default"}} -->
<div class="wp-block-group sidebar-section is-events has-sand-background-color has-background">
	<!-- wp:heading {"level":4,"className":"sidebar-section__title"} -->
	<h4 class="wp-block-heading sidebar-section__title"><?php esc_html_e( 'Wydarzenia', 'eichendorff' ); ?></h4>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"calendar_event","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"className":"event-list","layout":{"type":"default"}} -->
			<!-- wp:group {"className":"event-item","layout":{"type":"default"}} -->
			<div class="wp-block-group event-item">
				<!-- wp:group {"className":"date-badge","layout":{"type":"default"}} -->
				<div class="wp-block-group date-badge">
					<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"eichendorff/event-date","args":{"format":"M"}}}},"className":"date-badge__month"} -->
					<p class="date-badge__month"></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"eichendorff/event-date","args":{"format":"d"}}}},"className":"date-badge__day"} -->
					<p class="date-badge__day"></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"event-item__body","layout":{"type":"default"}} -->
				<div class="wp-block-group event-item__body">
					<!-- wp:post-title {"level":3,"className":"event-item__title"} /-->
					<!-- wp:post-excerpt {"className":"event-item__text"} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sidebar-section is-menu","backgroundColor":"sand","layout":{"type":"default"}} -->
<div class="wp-block-group sidebar-section is-menu has-sand-background-color has-background">
	<!-- wp:heading {"level":4,"className":"sidebar-section__title"} -->
	<h4 class="wp-block-heading sidebar-section__title"><?php esc_html_e( 'Menu dodatkowe', 'eichendorff' ); ?></h4>
	<!-- /wp:heading -->

	<!-- wp:navigation {"overlayMenu":"never","className":"sidebar-nav","layout":{"type":"flex","orientation":"vertical"}} -->
		<!-- wp:navigation-link {"label":"Home","url":"/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"Fundacja","url":"/fundacja/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"Filmy","url":"/filmy/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"Kontakt","url":"/kontakt/","kind":"custom"} /-->
	<!-- /wp:navigation -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"sidebar-section is-partners","backgroundColor":"sand","layout":{"type":"default"}} -->
<div class="wp-block-group sidebar-section is-partners has-sand-background-color has-background">
	<!-- wp:heading {"level":4,"className":"sidebar-section__title"} -->
	<h4 class="wp-block-heading sidebar-section__title"><?php esc_html_e( 'Partnerzy', 'eichendorff' ); ?></h4>
	<!-- /wp:heading -->

	<!-- wp:list {"className":"is-style-square partner-list"} -->
	<ul class="wp-block-list is-style-square partner-list">
		<!-- wp:list-item -->
		<li><a href="https://spgrzegorzowice.pl/">Szkoła Podstawowa w Grzegorzowicach</a></li>
		<!-- /wp:list-item -->

		<!-- wp:list-item -->
		<li><a href="https://skgd.pl/">Towarzystwo Społeczno-Kulturalne Niemców na Śląsku Opolskim</a></li>
		<!-- /wp:list-item -->

		<!-- wp:list-item -->
		<li><a href="https://pspwalce.walce.pl/24/strona-glowna.html">Publiczna Szkoła Podstawowa w Walcach</a></li>
		<!-- /wp:list-item -->
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
