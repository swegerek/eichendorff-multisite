<?php
/**
 * Eichendorff theme bootstrap.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

define( 'EICHENDORFF_VERSION', wp_get_theme( 'eichendorff' )->get( 'Version' ) );

require_once get_theme_file_path( 'inc/german-link.php' );
require_once get_theme_file_path( 'inc/events.php' );

/**
 * Registers theme supports.
 */
function eichendorff_setup(): void {
	add_editor_style( 'assets/css/main.css' );
	add_image_size( 'eichendorff-card', 480, 400, true );

	register_block_style(
		'core/list',
		array(
			'name'  => 'square',
			'label' => __( 'Kwadraty', 'eichendorff' ),
		)
	);

	register_block_style(
		'core/list',
		array(
			'name'  => 'square-columns',
			'label' => __( 'Kwadraty w kolumnach', 'eichendorff' ),
		)
	);

	register_block_style(
		'core/gallery',
		array(
			'name'  => 'grid',
			'label' => __( 'Siatka', 'eichendorff' ),
		)
	);
}
add_action( 'after_setup_theme', 'eichendorff_setup' );

/**
 * Keeps the characters editors typed in post and page content, as Elementor showed them on production.
 *
 * Block themes texturize the whole rendered template, so the content block renders as `article`
 * and that element is excluded here.
 *
 * @param string[] $tags Elements whose text is not texturized.
 */
function eichendorff_no_texturize_content( array $tags ): array {
	$tags[] = 'article';

	return $tags;
}
add_filter( 'no_texturize_tags', 'eichendorff_no_texturize_content' );
remove_filter( 'the_content', 'wptexturize' );

/**
 * Enqueues the theme assets.
 */
function eichendorff_enqueue_assets(): void {
	wp_enqueue_style( 'eichendorff', get_theme_file_uri( 'assets/css/main.css' ), array(), EICHENDORFF_VERSION );
	wp_enqueue_script( 'eichendorff-navigation', get_theme_file_uri( 'assets/js/navigation.js' ), array(), EICHENDORFF_VERSION, array( 'strategy' => 'defer' ) );

	if ( is_home() ) {
		wp_enqueue_script( 'eichendorff-slider', get_theme_file_uri( 'assets/js/slider.js' ), array(), EICHENDORFF_VERSION, array( 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'eichendorff_enqueue_assets' );

/**
 * Enqueues the script that keeps the editor preview close to the front end.
 */
function eichendorff_enqueue_editor_assets(): void {
	wp_enqueue_script(
		'eichendorff-editor',
		get_theme_file_uri( 'assets/js/editor.js' ),
		array( 'wp-autop', 'wp-block-editor', 'wp-blocks', 'wp-compose', 'wp-core-data', 'wp-date', 'wp-dom', 'wp-element', 'wp-hooks' ),
		EICHENDORFF_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'eichendorff_enqueue_editor_assets' );

/**
 * Tells the browser how wide the images of a grid gallery are, so it loads small files.
 *
 * Images in the grid are 1/4 of the content column, 1/2 below 1024px and full width below 767px.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 */
function eichendorff_grid_gallery_sizes( string $block_content, array $block ): string {
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'is-style-grid' ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );

	while ( $tags->next_tag( 'img' ) ) {
		$tags->set_attribute( 'sizes', '(max-width: 766px) calc(100vw - 32px), (max-width: 1023px) calc(50vw - 21px), (max-width: 1024px) 237px, 181px' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/gallery', 'eichendorff_grid_gallery_sizes', 10, 2 );

/**
 * Keeps `sizes="auto"` off grid gallery images.
 *
 * Browsers without `sizes="auto"` treat the whole list as invalid and load files for the full viewport width.
 *
 * @param string $image Image tag.
 */
function eichendorff_grid_gallery_no_auto_sizes( string $image ): string {
	return str_replace( 'sizes="auto, (max-width: 766px) calc(100vw - 32px)', 'sizes="(max-width: 766px) calc(100vw - 32px)', $image );
}
add_filter( 'wp_content_img_tag', 'eichendorff_grid_gallery_no_auto_sizes' );

/**
 * Shows the whole hand-written excerpt with its formatting.
 *
 * The block trims excerpts to 55 words, strips tags and wraps them in a paragraph.
 * With the `is-hand-written` class the block shows nothing when there is no hand-written excerpt,
 * so a post does not repeat the start of its content.
 *
 * @param string   $block_content Rendered block.
 * @param array    $block         Parsed block.
 * @param WP_Block $instance      Block instance.
 */
function eichendorff_full_excerpt( string $block_content, array $block, WP_Block $instance ): string {
	$excerpt   = get_post_field( 'post_excerpt', $instance->context['postId'] ?? 0 );
	$classname = $block['attrs']['className'] ?? '';

	if ( ! $excerpt ) {
		return str_contains( $classname, 'is-hand-written' ) ? '' : $block_content;
	}

	$classes = trim( 'wp-block-post-excerpt ' . $classname );

	return sprintf( '<div class="%s">%s</div>', esc_attr( $classes ), wpautop( wp_kses_post( $excerpt ) ) );
}
add_filter( 'render_block_core/post-excerpt', 'eichendorff_full_excerpt', 10, 3 );

/**
 * Keeps the production breadcrumbs: `Home` only on the home page, with the production label,
 * and only the title on a post.
 *
 * @param array $items Breadcrumb items.
 */
function eichendorff_breadcrumb_home_label( array $items ): array {
	if ( ! $items ) {
		return $items;
	}

	if ( is_singular( 'post' ) ) {
		return array_slice( $items, -1 );
	}

	if ( ! is_front_page() && ! is_home() ) {
		return array_slice( $items, 1 );
	}

	$items[0]['label'] = 'Home';

	return $items;
}
add_filter( 'block_core_breadcrumbs_items', 'eichendorff_breadcrumb_home_label' );

/**
 * Marks custom navigation links that point to the current page.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 */
function eichendorff_mark_current_menu_item( string $block_content, array $block ): string {
	$url = $block['attrs']['url'] ?? '';

	if ( ! $url || '#' === $url ) {
		return $block_content;
	}

	$current = trailingslashit( (string) wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) );

	if ( trailingslashit( $url ) !== $current ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );

	if ( $tags->next_tag( 'li' ) ) {
		$tags->add_class( 'current-menu-item' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'eichendorff_mark_current_menu_item', 10, 2 );
