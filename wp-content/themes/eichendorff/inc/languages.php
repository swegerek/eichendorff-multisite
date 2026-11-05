<?php
/**
 * Language versions: theme translations and the Polish and German sites of the network.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the theme translations from `languages/`.
 */
function eichendorff_load_textdomain(): void {
	load_theme_textdomain( 'eichendorff', get_theme_file_path( 'languages' ) );
}
add_action( 'after_setup_theme', 'eichendorff_load_textdomain' );

/**
 * Tells whether the current site is the German one.
 */
function eichendorff_is_german(): bool {
	return str_starts_with( determine_locale(), 'de' );
}

/**
 * Returns a root-relative address on the current site, such as `/de/kontakt/`.
 *
 * @param string $path Path on the site, starting and ending with a slash.
 */
function eichendorff_site_path( string $path ): string {
	return untrailingslashit( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ) . $path;
}

/**
 * Prints Navigation link blocks for a list of menu items.
 *
 * Each item is `array( label, path )`, or `array( label, null, children )` for a submenu.
 *
 * @param array $items Menu items.
 * @param int   $depth Indentation of the printed blocks.
 */
function eichendorff_print_menu( array $items, int $depth ): void {
	$indent = str_repeat( "\t", $depth );

	foreach ( $items as $item ) {
		if ( isset( $item[2] ) ) {
			$attributes = array(
				'label' => $item[0],
				'url'   => '#',
				'kind'  => 'custom',
			);
			echo $indent . '<!-- wp:navigation-submenu ' . wp_json_encode( $attributes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . " -->\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
			eichendorff_print_menu( $item[2], $depth + 1 );
			echo $indent . "<!-- /wp:navigation-submenu -->\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
			continue;
		}

		$attributes = array(
			'label' => $item[0],
			'url'   => eichendorff_site_path( $item[1] ),
			'kind'  => 'custom',
		);
		echo $indent . '<!-- wp:navigation-link ' . wp_json_encode( $attributes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . " /-->\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
	}
}

/**
 * Returns the translated block labels, keyed by the Polish text saved in the templates.
 *
 * Pagination and post navigation stay in the templates, because inside a pattern
 * they lose the query context; their labels are translated here instead.
 */
function eichendorff_block_labels(): array {
	return array(
		'Poprzedni'     => __( 'Poprzedni', 'eichendorff' ),
		'Następny'      => __( 'Następny', 'eichendorff' ),
		'Czytaj więcej' => __( 'Czytaj więcej', 'eichendorff' ),
	);
}

/**
 * Translates the labels of pagination, post navigation and Read more blocks.
 *
 * @param array $parsed_block Parsed block.
 */
function eichendorff_translate_block_labels( array $parsed_block ): array {
	$attributes = array(
		'core/query-pagination-previous' => 'label',
		'core/query-pagination-next'     => 'label',
		'core/post-navigation-link'      => 'label',
		'core/read-more'                 => 'content',
	);
	$name       = $parsed_block['blockName'] ?? '';

	if ( ! isset( $attributes[ $name ] ) ) {
		return $parsed_block;
	}

	$attribute = $attributes[ $name ];
	$labels    = eichendorff_block_labels();
	$value     = $parsed_block['attrs'][ $attribute ] ?? '';

	if ( isset( $labels[ $value ] ) ) {
		$parsed_block['attrs'][ $attribute ] = $labels[ $value ];
	}

	return $parsed_block;
}
add_filter( 'render_block_data', 'eichendorff_translate_block_labels' );

/**
 * Site IDs of the language versions in the network.
 */
const EICHENDORFF_SITES = array(
	1 => 'pl',
	2 => 'de',
);

/**
 * Meta key with the ID of the counterpart on the other site.
 */
const EICHENDORFF_TRANSLATION_META = '_eichendorff_translation';

/**
 * Returns the ID of the other language site.
 */
function eichendorff_other_site(): int {
	return 1 === get_current_blog_id() ? 2 : 1;
}

/**
 * Returns the language code of a site, such as `pl`.
 *
 * @param int $site_id Site ID.
 */
function eichendorff_site_language( int $site_id ): string {
	return EICHENDORFF_SITES[ $site_id ] ?? 'pl';
}

/**
 * Returns the counterpart of a post on the other site, or 0.
 *
 * @param int $post_id Post on the current site.
 */
function eichendorff_get_translation( int $post_id ): int {
	return (int) get_post_meta( $post_id, EICHENDORFF_TRANSLATION_META, true );
}

/**
 * Removes the link from a post to a counterpart, when it points there.
 *
 * Runs on the current site.
 *
 * @param int $post_id   Post that may hold the link.
 * @param int $target_id Counterpart the link must point to.
 */
function eichendorff_unlink_if( int $post_id, int $target_id ): void {
	if ( eichendorff_get_translation( $post_id ) === $target_id ) {
		delete_post_meta( $post_id, EICHENDORFF_TRANSLATION_META );
	}
}

/**
 * Links a post with its counterpart on the other site, both ways.
 *
 * Earlier links of either post are removed, so each post has at most one counterpart.
 * A target of 0 removes the link.
 *
 * @param int $post_id   Post on the current site.
 * @param int $target_id Post on the other site, or 0.
 */
function eichendorff_set_translation( int $post_id, int $target_id ): void {
	$other = eichendorff_other_site();
	$old   = eichendorff_get_translation( $post_id );

	if ( $old && $old !== $target_id ) {
		switch_to_blog( $other );
		eichendorff_unlink_if( $old, $post_id );
		restore_current_blog();
	}

	if ( $target_id ) {
		switch_to_blog( $other );
		$previous = eichendorff_get_translation( $target_id );
		update_post_meta( $target_id, EICHENDORFF_TRANSLATION_META, $post_id );
		restore_current_blog();

		if ( $previous && $previous !== $post_id ) {
			eichendorff_unlink_if( $previous, $target_id );
		}

		update_post_meta( $post_id, EICHENDORFF_TRANSLATION_META, $target_id );
		return;
	}

	delete_post_meta( $post_id, EICHENDORFF_TRANSLATION_META );
}

/**
 * Removes the link of a post that goes to the trash or is deleted.
 *
 * @param int $post_id Post ID.
 */
function eichendorff_unlink_removed_post( int $post_id ): void {
	if ( eichendorff_get_translation( $post_id ) ) {
		eichendorff_set_translation( $post_id, 0 );
	}
}
add_action( 'wp_trash_post', 'eichendorff_unlink_removed_post' );
add_action( 'before_delete_post', 'eichendorff_unlink_removed_post' );

/**
 * Returns the published counterpart of the current post on the other site.
 *
 * @return array{url: string, translated: bool} Address of the counterpart, or of the other site's home page.
 */
function eichendorff_current_translation(): array {
	static $result = null;

	if ( null !== $result ) {
		return $result;
	}

	$other  = eichendorff_other_site();
	$result = array(
		'url'        => get_home_url( $other, '/' ),
		'translated' => false,
	);

	if ( ( is_front_page() || is_home() ) && ! is_paged() ) {
		$result['translated'] = true;
		return $result;
	}

	if ( ! is_singular() ) {
		return $result;
	}

	$target = eichendorff_get_translation( get_queried_object_id() );

	if ( ! $target ) {
		return $result;
	}

	switch_to_blog( $other );
	if ( 'publish' === get_post_status( $target ) ) {
		$result = array(
			'url'        => get_permalink( $target ),
			'translated' => true,
		);
	}
	restore_current_blog();

	return $result;
}

/**
 * Points the header flag at the counterpart of the current page on the other site.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 */
function eichendorff_language_link( string $block_content, array $block ): string {
	if ( ! str_contains( $block['attrs']['className'] ?? '', 'site-header__language' ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'href', eichendorff_current_translation()['url'] );
		$tags->set_attribute( 'hreflang', eichendorff_site_language( eichendorff_other_site() ) );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/image', 'eichendorff_language_link', 10, 2 );

/**
 * Prints the alternate language versions of pages that have a counterpart.
 */
function eichendorff_hreflang_links(): void {
	$translation = eichendorff_current_translation();

	if ( ! $translation['translated'] ) {
		return;
	}

	$current = ( is_front_page() || is_home() ) ? home_url( '/' ) : get_permalink( get_queried_object_id() );
	$links   = array(
		eichendorff_site_language( get_current_blog_id() ) => $current,
		eichendorff_site_language( eichendorff_other_site() ) => $translation['url'],
	);
	ksort( $links );

	foreach ( $links as $language => $url ) {
		printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( $language ), esc_url( $url ) );
	}
}
add_action( 'wp_head', 'eichendorff_hreflang_links' );

/**
 * Post types whose posts can have a counterpart on the other site.
 */
const EICHENDORFF_TRANSLATED_TYPES = array( 'post', 'page' );

/**
 * Adds the counterpart box to the post and page editor.
 *
 * @param string $post_type Post type of the edited post.
 */
function eichendorff_add_translation_box( string $post_type ): void {
	if ( ! in_array( $post_type, EICHENDORFF_TRANSLATED_TYPES, true ) ) {
		return;
	}

	$title = 'de' === eichendorff_site_language( eichendorff_other_site() )
		? __( 'Odpowiednik w wersji niemieckiej', 'eichendorff' )
		: __( 'Odpowiednik w wersji polskiej', 'eichendorff' );

	add_meta_box(
		'eichendorff-translation',
		$title,
		'eichendorff_render_translation_box',
		$post_type,
		'side',
		'default',
		array( '__back_compat_meta_box' => false )
	);
}
add_action( 'add_meta_boxes', 'eichendorff_add_translation_box' );

/**
 * Prints the counterpart box: a list of the other site's posts of the same type.
 *
 * @param WP_Post $post Edited post.
 */
function eichendorff_render_translation_box( WP_Post $post ): void {
	$current = eichendorff_get_translation( $post->ID );

	switch_to_blog( eichendorff_other_site() );
	$candidates = get_posts(
		array(
			'post_type'   => $post->post_type,
			'post_status' => array( 'publish', 'future', 'draft', 'pending', 'private' ),
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);
	restore_current_blog();

	wp_nonce_field( 'eichendorff_translation', 'eichendorff_translation_nonce' );
	?>
	<label class="screen-reader-text" for="eichendorff-translation-select"><?php esc_html_e( 'Odpowiednik', 'eichendorff' ); ?></label>
	<select id="eichendorff-translation-select" name="eichendorff_translation" style="width:100%">
		<option value="0"><?php esc_html_e( '— brak —', 'eichendorff' ); ?></option>
		<?php foreach ( $candidates as $candidate ) : ?>
			<option value="<?php echo esc_attr( (string) $candidate->ID ); ?>" <?php selected( $current, $candidate->ID ); ?>>
				<?php echo esc_html( sprintf( '%s (%s, %s)', $candidate->post_title, get_the_date( 'Y-m-d', $candidate ), $candidate->post_name ) ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'Zmiana obejmuje obie wersje językowe.', 'eichendorff' ); ?></p>
	<script>
		// The block editor saves meta boxes only with a changed post, so a new choice marks the post as changed.
		document.getElementById( 'eichendorff-translation-select' ).addEventListener( 'change', function () {
			if ( window.wp && wp.data && wp.data.dispatch( 'core/editor' ) ) {
				wp.data.dispatch( 'core/editor' ).editPost( { meta: { _eichendorff_translation_changed: Date.now() } } );
			}
		} );
	</script>
	<?php
}

/**
 * Saves the counterpart chosen in the editor, on both sites.
 *
 * @param int $post_id Saved post.
 */
function eichendorff_save_translation_box( int $post_id ): void {
	if ( ! isset( $_POST['eichendorff_translation_nonce'], $_POST['eichendorff_translation'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['eichendorff_translation_nonce'] ) ), 'eichendorff_translation' )
		|| wp_is_post_revision( $post_id )
		|| wp_is_post_autosave( $post_id )
		|| ! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	$target = absint( wp_unslash( $_POST['eichendorff_translation'] ) );

	if ( $target === eichendorff_get_translation( $post_id ) ) {
		return;
	}

	if ( $target ) {
		$post_type = get_post_type( $post_id );

		switch_to_blog( eichendorff_other_site() );
		$valid = get_post_type( $target ) === $post_type && current_user_can( 'edit_post', $target );
		restore_current_blog();

		if ( ! $valid ) {
			return;
		}
	}

	eichendorff_set_translation( $post_id, $target );
}
add_action( 'save_post', 'eichendorff_save_translation_box' );
