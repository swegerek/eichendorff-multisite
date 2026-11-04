<?php
/**
 * Media library helpers shared by the import tools.
 *
 * Files are deduplicated by their SHA-256, kept in the `_eichendorff_sha256` meta.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

const EICHENDORFF_MAX_MEDIA_BYTES = 100 * MB_IN_BYTES;

/**
 * Returns the attachment with the given file hash, or 0.
 *
 * @param string $sha256 File hash.
 */
function eichendorff_media_by_hash( string $sha256 ): int {
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_eichendorff_sha256',
			'meta_value'  => $sha256,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);

	return $found ? $found[0] : 0;
}

/**
 * Adds a file to the media library and returns the attachment, or 0 on failure.
 *
 * The file is moved into the uploads folder, so pass a temporary copy.
 *
 * @param string $tmp     Temporary file.
 * @param string $name    File name in the library.
 * @param int    $post_id Post the attachment belongs to.
 * @param array  $data    Attachment fields, such as `post_title`.
 * @param string $sha256  File hash.
 */
function eichendorff_media_add( string $tmp, string $name, int $post_id, array $data, string $sha256 ): int {
	$attachment = media_handle_sideload(
		array(
			'name'     => $name,
			'tmp_name' => $tmp,
		),
		$post_id,
		null,
		$data
	);

	if ( is_wp_error( $attachment ) ) {
		WP_CLI::warning( "$name: " . $attachment->get_error_message() );
		return 0;
	}

	update_post_meta( $attachment, '_eichendorff_sha256', $sha256 );

	return $attachment;
}

/**
 * Returns the attachment for a file on production, downloading it when it is new.
 *
 * @param string $url     File address.
 * @param int    $post_id Post a new attachment belongs to.
 * @param string $title   Title of a new attachment; the file name when empty.
 */
function eichendorff_media_from_url( string $url, int $post_id, string $title = '' ): int {
	$tmp = download_url( $url, 300 );

	if ( is_wp_error( $tmp ) ) {
		WP_CLI::warning( "$url: " . $tmp->get_error_message() );
		return 0;
	}

	$sha256 = hash_file( 'sha256', $tmp );
	$found  = eichendorff_media_by_hash( $sha256 );

	if ( $found ) {
		wp_delete_file( $tmp );
		return $found;
	}

	$name = rawurldecode( wp_basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ) );

	return eichendorff_media_add( $tmp, $name, $post_id, array( 'post_title' => $title ? $title : pathinfo( $name, PATHINFO_FILENAME ) ), $sha256 );
}
