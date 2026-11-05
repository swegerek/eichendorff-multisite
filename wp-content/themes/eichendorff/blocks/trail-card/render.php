<?php
/**
 * Trail card: name with an icon, route, distance and marking on the left, route files on the right.
 *
 * @package Eichendorff
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_color = static fn( string $value, string $fallback ): string => preg_match( '/^#[0-9a-f]{3,8}$/i', $value ) ? $value : $fallback;

$eichendorff_style = sprintf(
	'--trail-icon-color:%s;--trail-icon-background:%s;--trail-marker-color:%s',
	$eichendorff_color( $attributes['iconColor'] ?? '', '#653724' ),
	$eichendorff_color( $attributes['iconBackground'] ?? '', '#C8B9A040' ),
	$eichendorff_color( $attributes['markerColor'] ?? '', '#653724' )
);

$eichendorff_files = array(
	'Format GPX' => ( $attributes['gpxId'] ?? 0 ) ? wp_get_attachment_url( $attributes['gpxId'] ) : ( $attributes['gpxUrl'] ?? '' ),
	'Format KML' => ( $attributes['kmlId'] ?? 0 ) ? wp_get_attachment_url( $attributes['kmlId'] ) : ( $attributes['kmlUrl'] ?? '' ),
);

$eichendorff_wrapper = get_block_wrapper_attributes(
	array(
		'class' => 'long' === ( $attributes['markerStyle'] ?? '' ) ? 'is-marker-long' : '',
		'style' => $eichendorff_style,
	)
);
?>
<article <?php echo $eichendorff_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="wp-block-eichendorff-trail-card__info">
		<div class="wp-block-eichendorff-trail-card__title">
			<span class="wp-block-eichendorff-trail-card__icon"><?php echo wp_get_icon( $attributes['icon'] ?? '', array( 'size' => null ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="wp-block-eichendorff-trail-card__name"><?php echo wp_kses_post( $attributes['title'] ?? '' ); ?></h3>
		</div>
		<div class="wp-block-eichendorff-trail-card__fact">
			<p class="wp-block-eichendorff-trail-card__label"><?php esc_html_e( 'Trasa', 'eichendorff' ); ?></p>
			<p class="wp-block-eichendorff-trail-card__value"><?php echo wp_kses_post( $attributes['route'] ?? '' ); ?></p>
		</div>
		<div class="wp-block-eichendorff-trail-card__fact">
			<p class="wp-block-eichendorff-trail-card__label"><?php esc_html_e( 'Dystans', 'eichendorff' ); ?></p>
			<p class="wp-block-eichendorff-trail-card__value"><?php echo wp_kses_post( $attributes['distance'] ?? '' ); ?></p>
		</div>
		<div class="wp-block-eichendorff-trail-card__fact wp-block-eichendorff-trail-card__fact--marker">
			<p class="wp-block-eichendorff-trail-card__label"><?php esc_html_e( 'Oznaczenie szlaku', 'eichendorff' ); ?></p>
			<span class="wp-block-eichendorff-trail-card__marker"></span>
		</div>
	</div>
	<div class="wp-block-eichendorff-trail-card__download">
		<p class="wp-block-eichendorff-trail-card__label"><?php esc_html_e( 'Pobierz trasę', 'eichendorff' ); ?></p>
		<?php foreach ( array_filter( $eichendorff_files ) as $eichendorff_label => $eichendorff_url ) : ?>
			<div class="wp-block-button is-style-download"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $eichendorff_url ); ?>" download><?php echo esc_html( $eichendorff_label ); ?></a></div>
		<?php endforeach; ?>
	</div>
</article>
