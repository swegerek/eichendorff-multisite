<?php
/**
 * Imports Polish posts from the local copy of the production site.
 *
 * Run with: wp eval-file tools/import-posts.php <copy-dir>/pl --user=1
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/lib/media.php';

$eichendorff_dir = rtrim( $args[0], '/\\' );
$eichendorff_tax = json_decode( file_get_contents( "$eichendorff_dir/_taxonomies.json" ), true );

/**
 * Returns the local category IDs for production category IDs.
 *
 * @param array $ids        Production category IDs.
 * @param array $categories Production categories.
 */
function eichendorff_import_categories( array $ids, array $categories ): array {
	$terms = array();

	foreach ( $categories as $category ) {
		if ( ! in_array( $category['id'], $ids, true ) ) {
			continue;
		}
		$term    = term_exists( $category['slug'], 'category' ) ?: wp_insert_term( $category['name'], 'category', array( 'slug' => $category['slug'] ) );
		$terms[] = (int) $term['term_id'];
	}

	return $terms;
}

/**
 * Returns the attachment for a file, adding it to the library when it is new.
 *
 * @param array  $media   Entry from media.json.
 * @param string $dir     Post folder.
 * @param int    $post_id Post the new attachment belongs to.
 */
function eichendorff_import_media( array $media, string $dir, int $post_id ): int {
	$found = eichendorff_media_by_hash( $media['sha256'] );

	if ( $found ) {
		return $found;
	}

	$tmp = wp_tempnam( $media['file'] );
	copy( "$dir/media/{$media['file']}", $tmp );

	$attachment = eichendorff_media_add(
		$tmp,
		$media['file'],
		$post_id,
		array(
			'post_title'   => html_entity_decode( (string) $media['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
			'post_excerpt' => trim( wp_strip_all_tags( (string) $media['caption'] ) ),
		),
		$media['sha256']
	);

	if ( $attachment ) {
		update_post_meta( $attachment, '_wp_attachment_image_alt', (string) $media['alt'] );
	}

	return $attachment;
}

$eichendorff_folders = glob( "$eichendorff_dir/posts/*", GLOB_ONLYDIR );
sort( $eichendorff_folders );

foreach ( $eichendorff_folders as $eichendorff_folder ) {
	$eichendorff_post  = json_decode( file_get_contents( "$eichendorff_folder/post.json" ), true );
	$eichendorff_media = json_decode( file_get_contents( "$eichendorff_folder/media.json" ), true );

	$eichendorff_existing = get_posts(
		array(
			'post_type'   => 'post',
			'post_status' => 'any',
			'meta_key'    => '_eichendorff_source',
			'meta_value'  => "pl:{$eichendorff_post['id']}",
			'fields'      => 'ids',
		)
	);

	if ( $eichendorff_existing ) {
		continue;
	}

	$eichendorff_id = wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_title'    => html_entity_decode( $eichendorff_post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
			'post_name'     => $eichendorff_post['slug'],
			'post_excerpt'  => trim( $eichendorff_post['excerpt']['rendered'] ),
			'post_date'     => str_replace( 'T', ' ', $eichendorff_post['date'] ),
			'post_date_gmt' => str_replace( 'T', ' ', $eichendorff_post['date_gmt'] ),
			'post_category' => eichendorff_import_categories( $eichendorff_post['categories'], $eichendorff_tax['categories'] ),
		),
		true
	);

	if ( is_wp_error( $eichendorff_id ) ) {
		WP_CLI::warning( "{$eichendorff_post['slug']}: " . $eichendorff_id->get_error_message() );
		continue;
	}

	update_post_meta( $eichendorff_id, '_eichendorff_source', "pl:{$eichendorff_post['id']}" );

	$eichendorff_content = $eichendorff_post['content']['rendered'];

	foreach ( $eichendorff_media as $eichendorff_file ) {
		if ( $eichendorff_file['bytes'] > EICHENDORFF_MAX_MEDIA_BYTES ) {
			continue;
		}

		$eichendorff_attachment = eichendorff_import_media( $eichendorff_file, $eichendorff_folder, $eichendorff_id );

		if ( ! $eichendorff_attachment ) {
			continue;
		}

		foreach ( $eichendorff_file['referenced_urls'] as $eichendorff_url ) {
			$eichendorff_content = str_replace( $eichendorff_url, wp_get_attachment_url( $eichendorff_attachment ), $eichendorff_content );
		}

		if ( (int) $eichendorff_file['attachment_id'] === (int) $eichendorff_post['featured_media'] ) {
			set_post_thumbnail( $eichendorff_id, $eichendorff_attachment );
		}
	}

	$eichendorff_content = preg_replace( '/\s(srcset|sizes)="[^"]*"/', '', $eichendorff_content );
	$eichendorff_content = preg_replace( '#https://eichendorff\.pl/(?!de/|wp-content/)#', home_url( '/' ), $eichendorff_content );

	wp_update_post(
		array(
			'ID'           => $eichendorff_id,
			'post_content' => $eichendorff_content,
		)
	);

	WP_CLI::log( "{$eichendorff_id} {$eichendorff_post['slug']}" );
}

WP_CLI::success( 'Import zakończony.' );
