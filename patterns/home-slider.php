<?php
/**
 * Title: Slider strony głównej
 * Slug: eichendorff/home-slider
 * Inserter: no
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_title       = __( 'Witaj na stronie GCKiS', 'eichendorff' );
$eichendorff_description = __( 'Oficjalna strona Górnoslaskiego Centrum Kultury i Spotkań im. Josepha von Eichendorff w Łubowicach', 'eichendorff' );
$eichendorff_slides      = array(
	array( 'intro_4.jpg', '' ),
	array( 'intro_3.jpg', '' ),
	array( 'intro_2.jpg', 'has-ken-burns' ),
);
?>
<!-- wp:group {"className":"home-slider","layout":{"type":"default"}} -->
<div class="wp-block-group home-slider">
	<?php foreach ( $eichendorff_slides as $eichendorff_index => [ $eichendorff_file, $eichendorff_class ] ) : ?>
		<?php $eichendorff_url = get_theme_file_uri( "assets/images/$eichendorff_file" ); ?>
		<!-- wp:cover {"url":"<?php echo esc_url( $eichendorff_url ); ?>","dimRatio":60,"overlayColor":"bark","isUserOverlayColor":true,"isDark":true,"className":"<?php echo esc_attr( $eichendorff_class ); ?>","layout":{"type":"default"}} -->
		<div class="wp-block-cover <?php echo esc_attr( $eichendorff_class ); ?>"><span aria-hidden="true" class="wp-block-cover__background has-bark-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $eichendorff_url ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
			<?php if ( 0 === $eichendorff_index ) : ?>
				<!-- wp:heading {"level":1,"className":"home-slider__title","textColor":"white","fontFamily":"inter"} -->
				<h1 class="wp-block-heading home-slider__title has-white-color has-text-color has-inter-font-family"><?php echo esc_html( $eichendorff_title ); ?></h1>
				<!-- /wp:heading -->
			<?php else : ?>
				<!-- wp:paragraph {"className":"home-slider__title","textColor":"white","fontFamily":"inter"} -->
				<p class="home-slider__title has-white-color has-text-color has-inter-font-family"><?php echo esc_html( $eichendorff_title ); ?></p>
				<!-- /wp:paragraph -->
			<?php endif; ?>

			<!-- wp:paragraph {"className":"home-slider__text","textColor":"white","fontFamily":"inter"} -->
			<p class="home-slider__text has-white-color has-text-color has-inter-font-family"><?php echo esc_html( $eichendorff_description ); ?></p>
			<!-- /wp:paragraph -->
		</div></div>
		<!-- /wp:cover -->
	<?php endforeach; ?>
</div>
<!-- /wp:group -->
