<?php
/**
 * Language section: flag and language name over the text, which carries the `lang` attribute.
 *
 * @package Eichendorff
 *
 * @var array  $attributes Block attributes.
 * @var string $content    Text of the section.
 */

defined( 'ABSPATH' ) || exit;

$eichendorff_languages = array(
	'pl' => array( 'Polski', 'flag-pl' ),
	'de' => array( 'Deutsch', 'flag-de' ),
	'cs' => array( 'Čeština', 'flag-cz' ),
	'en' => array( 'English', 'flag-gb' ),
);
$eichendorff_language  = isset( $eichendorff_languages[ $attributes['language'] ?? '' ] ) ? $attributes['language'] : 'pl';

[ $eichendorff_name, $eichendorff_flag ] = $eichendorff_languages[ $eichendorff_language ];
?>
<section <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<p class="wp-block-eichendorff-language-section__name"><?php echo wp_get_icon( "eichendorff/$eichendorff_flag", array( 'size' => null ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span lang="<?php echo esc_attr( $eichendorff_language ); ?>"><?php echo esc_html( $eichendorff_name ); ?></span></p>
	<div class="wp-block-eichendorff-language-section__text" lang="<?php echo esc_attr( $eichendorff_language ); ?>"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
</section>
