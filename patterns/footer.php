<?php
/**
 * Title: Stopka
 * Slug: eichendorff/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_logos = array(
	'BMI_Fz_2025_WebSVG_de.svg'       => 'Bundesministerium des Innern',
	'Logo_VDG_color_acronim_base.svg' => 'VdG',
	'mswia_logo.png'                  => 'Ministerstwo Spraw Wewnętrznych i Administracji',
);
?>
<!-- wp:group {"tagName":"section","className":"site-footer__supporters","backgroundColor":"white","layout":{"type":"default"}} -->
<section class="wp-block-group site-footer__supporters has-white-background-color has-background">
	<!-- wp:group {"className":"site-container","layout":{"type":"default"}} -->
	<div class="wp-block-group site-container">
		<!-- wp:heading {"fontSize":"section","fontFamily":"lora"} -->
		<h2 class="wp-block-heading has-lora-font-family has-section-font-size"><?php esc_html_e( 'Instytucje wspierające', 'eichendorff' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:group {"className":"site-footer__logos","layout":{"type":"default"}} -->
		<div class="wp-block-group site-footer__logos">
			<?php foreach ( $eichendorff_logos as $eichendorff_file => $eichendorff_alt ) : ?>
				<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( "assets/images/$eichendorff_file" ) ); ?>" alt="<?php echo esc_attr( $eichendorff_alt ); ?>"/></figure>
				<!-- /wp:image -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"site-footer__main","textColor":"mist","gradient":"footer","fontSize":"small","fontFamily":"inter","layout":{"type":"default"}} -->
<section class="wp-block-group site-footer__main has-mist-color has-footer-gradient-background has-text-color has-background has-inter-font-family has-small-font-size">
	<!-- wp:group {"className":"site-footer__columns","layout":{"type":"default"}} -->
	<div class="wp-block-group site-footer__columns">
		<!-- wp:group {"className":"site-footer__column","layout":{"type":"default"}} -->
		<div class="wp-block-group site-footer__column">
			<!-- wp:heading {"level":3,"textColor":"cream","fontSize":"large","fontFamily":"lora"} -->
			<h3 class="wp-block-heading has-cream-color has-text-color has-lora-font-family has-large-font-size"><?php esc_html_e( 'Górnośląskie Centrum Kultury i Spotkań im. Josepha von Eichendorffa', 'eichendorff' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"site-footer__intro"} -->
			<p class="site-footer__intro"><?php esc_html_e( 'Miejsce spotkań, kultury i tradycji w sercu Górnego Śląska. Zapraszamy do udziału w naszych wydarzeniach kulturalnych.', 'eichendorff' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"site-footer__social"} -->
			<p class="site-footer__social"><a href="https://www.facebook.com/gckisonline"><?php esc_html_e( 'Obserwuj nas na Facebooku', 'eichendorff' ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"site-footer__column","layout":{"type":"default"}} -->
		<div class="wp-block-group site-footer__column">
			<!-- wp:heading {"level":3,"textColor":"cream","fontSize":"large","fontFamily":"lora"} -->
			<h3 class="wp-block-heading has-cream-color has-text-color has-lora-font-family has-large-font-size"><?php esc_html_e( 'Dane kontaktowe', 'eichendorff' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:list {"className":"site-footer__contact"} -->
			<ul class="wp-block-list site-footer__contact">
				<!-- wp:list-item {"className":"is-address"} -->
				<li class="is-address"><?php esc_html_e( 'Górnośląskie Centrum Kultury i Spotkań im. Josepha von Eichendorffa', 'eichendorff' ); ?><br>ul. Zamkowa 1-3<br>47-411 Łubowice</li>
				<!-- /wp:list-item -->

				<!-- wp:list-item {"className":"is-phone"} -->
				<li class="is-phone">tel: +48 32 410 6602<br>tel: +48 32 414 9207<br>tel: +48 32 414 9206</li>
				<!-- /wp:list-item -->

				<!-- wp:list-item {"className":"is-email"} -->
				<li class="is-email">eichendorffzentrum@wp.pl</li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"site-footer__column","layout":{"type":"default"}} -->
		<div class="wp-block-group site-footer__column">
			<!-- wp:heading {"level":3,"textColor":"cream","fontSize":"large","fontFamily":"lora"} -->
			<h3 class="wp-block-heading has-cream-color has-text-color has-lora-font-family has-large-font-size"><?php esc_html_e( 'Wesprzyj fundację', 'eichendorff' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Nr rachunku bieżącego:', 'eichendorff' ); ?><br>Nr konta : 91 2030 0045 1110 0000 0425 6670<br>(IBAN) PL 91 2030 0045 1110 0000 0425 6670<br>(BIC/SWIFT) PPABPLPK</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Nr rachunku walutowego:', 'eichendorff' ); ?><br>Nr konta : 27 1600 1462 1031 1819 6000 0003<br>(IBAN) PL 27 1600 1462 1031 1819 6000 0003<br>(BIC/SWIFT) PPABPLPK</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"site-footer__bottom","textColor":"stone","layout":{"type":"default"}} -->
	<div class="wp-block-group site-footer__bottom has-stone-color has-text-color">
		<!-- wp:paragraph {"className":"site-container"} -->
		<p class="site-container">Copyright © 2025 GCKiS Łubowice</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
