<?php
/**
 * Title: Nagłówek strony ze zdjęciem
 * Slug: eichendorff/page-hero
 * Categories: banner
 * Keywords: nagłówek, zdjęcie, landing, okładka
 * Description: Zdjęcie z zaokrągleniem, ikona w kole, tytuł strony i podtytuł na środku. Zdjęcie wybiera się na pasku narzędzi Okładki.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"dimRatio":10,"overlayColor":"white","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","isDark":false,"className":"is-style-hero"} -->
<div class="wp-block-cover is-light is-style-hero" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-white-background-color has-background-dim-10 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:icon {"icon":"eichendorff/trees"} /-->

<!-- wp:heading {"level":1,"placeholder":"Tytuł strony"} -->
<h1 class="wp-block-heading"></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"placeholder":"Podtytuł"} -->
<p></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->
