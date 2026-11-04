<?php
/**
 * Title: Karta aktualności
 * Slug: eichendorff/news-card
 * Inserter: no
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"news-card","layout":{"type":"default"}} -->
<div class="wp-block-group news-card">
	<!-- wp:group {"className":"date-badge","layout":{"type":"default"}} -->
	<div class="wp-block-group date-badge">
		<!-- wp:post-date {"format":"M","className":"date-badge__month"} /-->
		<!-- wp:post-date {"format":"d","className":"date-badge__day"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"news-card__body","layout":{"type":"default"}} -->
	<div class="wp-block-group news-card__body">
		<!-- wp:post-title {"level":3,"isLink":true,"className":"news-card__title"} /-->

		<!-- wp:pattern {"slug":"eichendorff/post-meta"} /-->

		<!-- wp:group {"className":"news-card__content","layout":{"type":"default"}} -->
		<div class="wp-block-group news-card__content">
			<!-- wp:group {"className":"news-card__text","layout":{"type":"default"}} -->
			<div class="wp-block-group news-card__text">
				<!-- wp:post-excerpt /-->
				<!-- wp:read-more {"content":"Czytaj więcej"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:post-featured-image {"sizeSlug":"eichendorff-card"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
