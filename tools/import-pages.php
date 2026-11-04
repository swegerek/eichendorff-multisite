<?php
/**
 * Imports Polish pages from production and turns their Elementor widgets into core blocks.
 *
 * Run with: wp eval-file tools/import-pages.php <path>… --user=1
 * Example:  wp eval-file tools/import-pages.php fundacja fundacja/zarzad --user=1
 *
 * Production responses are kept in uploads/eichendorff-import/pages/. Delete that folder
 * to fetch the pages again.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

const EICHENDORFF_SOURCE_SITE = 'https://eichendorff.pl';

/**
 * Returns the folder with the copies of production pages.
 */
function eichendorff_import_dir(): string {
	$dir = wp_upload_dir()['basedir'] . '/eichendorff-import/pages';
	wp_mkdir_p( $dir );

	return $dir;
}

/**
 * Returns all production pages keyed by path, from the local copy when it exists.
 */
function eichendorff_import_source_pages(): array {
	$index = eichendorff_import_dir() . '/index.json';

	if ( ! file_exists( $index ) ) {
		$response = wp_remote_get(
			EICHENDORFF_SOURCE_SITE . '/wp-json/wp/v2/pages?per_page=100&_fields=id,slug,parent,link,title,menu_order,content',
			array( 'timeout' => 60 )
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			WP_CLI::error( 'Nie udało się pobrać stron z produkcji.' );
		}

		$paths = array();

		foreach ( json_decode( wp_remote_retrieve_body( $response ), true ) as $page ) {
			file_put_contents( eichendorff_import_dir() . "/{$page['id']}.json", wp_json_encode( $page ) );
			$paths[ trim( (string) wp_parse_url( $page['link'], PHP_URL_PATH ), '/' ) ] = $page['id'];
		}

		file_put_contents( $index, wp_json_encode( $paths ) );
	}

	$pages = array();

	foreach ( json_decode( file_get_contents( $index ), true ) as $path => $id ) {
		$pages[ $path ] = json_decode( file_get_contents( eichendorff_import_dir() . "/$id.json" ), true );
	}

	return $pages;
}

/**
 * Returns the local page imported from a production page ID.
 *
 * @param int $source_id Production page ID.
 */
function eichendorff_import_find_page( int $source_id ): int {
	$found = get_posts(
		array(
			'post_type'   => 'page',
			'post_status' => 'any',
			'meta_key'    => '_eichendorff_source',
			'meta_value'  => "pl-page:$source_id",
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);

	return $found ? $found[0] : 0;
}

/**
 * Serializes a block with optional attributes around its HTML.
 *
 * @param string $name  Block name without the `core/` prefix.
 * @param array  $attrs Block attributes.
 * @param string $html  Inner HTML.
 */
function eichendorff_import_block( string $name, array $attrs, string $html ): string {
	$json = $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) : '';

	return "<!-- wp:$name$json -->\n$html\n<!-- /wp:$name -->";
}

/**
 * Returns the inner HTML of a node.
 *
 * @param DOMNode $node Node.
 */
function eichendorff_import_inner_html( DOMNode $node ): string {
	$html = '';

	foreach ( $node->childNodes as $child ) {
		$html .= $node->ownerDocument->saveHTML( $child );
	}

	return $html;
}

/**
 * Cleans inline HTML: keeps formatting and links, drops classes and styles, points links at this site.
 *
 * @param string $html Inline HTML.
 */
function eichendorff_import_inline( string $html ): string {
	$html = preg_replace( '#<span style="text-decoration: underline;">(.*?)</span>#s', '<u>$1</u>', $html );
	$html = wp_kses(
		$html,
		array(
			'a'      => array( 'href' => true ),
			'strong' => array(),
			'b'      => array(),
			'em'     => array(),
			'i'      => array(),
			'u'      => array(),
			'br'     => array(),
			'sub'    => array(),
			'sup'    => array(),
		)
	);
	$html = preg_replace_callback(
		'#href="' . preg_quote( EICHENDORFF_SOURCE_SITE, '#' ) . '(/(?!wp-content/|de/)[^"]*)"#',
		static fn( array $m ): string => 'href="' . esc_url( home_url( $m[1] ) ) . '"',
		$html
	);
	$html = preg_replace( '#^(\s|&nbsp;|\x{00A0}|<br\s*/?>)+|(\s|&nbsp;|\x{00A0}|<br\s*/?>)+$#u', '', $html );

	return $html;
}

/**
 * Returns a paragraph block, or an empty string when the text is empty.
 *
 * @param string $html  Inline HTML.
 * @param string $align Text alignment from production.
 */
function eichendorff_import_paragraph( string $html, string $align = '' ): string {
	$html = eichendorff_import_inline( $html );

	if ( '' === trim( wp_strip_all_tags( $html ) ) ) {
		return '';
	}

	if ( $align ) {
		return eichendorff_import_block( 'paragraph', array( 'align' => $align ), "<p class=\"has-text-align-$align\">$html</p>" );
	}

	return eichendorff_import_block( 'paragraph', array(), "<p>$html</p>" );
}

/**
 * Returns a heading block.
 *
 * @param int    $level Heading level.
 * @param string $html  Inline HTML.
 */
function eichendorff_import_heading( int $level, string $html ): string {
	$attrs = 2 === $level ? array() : array( 'level' => $level );

	return eichendorff_import_block( 'heading', $attrs, "<h$level class=\"wp-block-heading\">" . eichendorff_import_inline( $html ) . "</h$level>" );
}

/**
 * Returns a list block built from HTML list items, with nested lists.
 *
 * @param DOMElement $list      `ul` or `ol` element.
 * @param array      $attrs     Block attributes.
 * @param callable   $item_html Returns the inline HTML of an item.
 */
function eichendorff_import_list( DOMElement $list, array $attrs, callable $item_html ): string {
	$ordered = 'ol' === $list->tagName;
	$items   = array();

	foreach ( $list->childNodes as $item ) {
		if ( ! $item instanceof DOMElement || 'li' !== $item->tagName ) {
			continue;
		}

		$nested = '';

		foreach ( $item->childNodes as $child ) {
			if ( $child instanceof DOMElement && in_array( $child->tagName, array( 'ul', 'ol' ), true ) ) {
				$nested .= "\n" . eichendorff_import_list( $child, array(), 'eichendorff_import_list_item_text' );
				$item->removeChild( $child );
			}
		}

		$items[] = eichendorff_import_block( 'list-item', array(), '<li>' . eichendorff_import_inline( $item_html( $item ) ) . $nested . '</li>' );
	}

	if ( $ordered ) {
		$attrs = array( 'ordered' => true ) + $attrs;
	}

	$tag     = $ordered ? 'ol' : 'ul';
	$classes = trim( 'wp-block-list ' . ( $attrs['className'] ?? '' ) );

	return eichendorff_import_block( 'list', $attrs, "<$tag class=\"$classes\">" . implode( "\n", $items ) . "</$tag>" );
}

/**
 * Returns the HTML of a plain list item.
 *
 * @param DOMElement $item List item.
 */
function eichendorff_import_list_item_text( DOMElement $item ): string {
	return eichendorff_import_inner_html( $item );
}

/**
 * Returns the HTML of an Elementor icon list item: its text, linked when the item is a link.
 *
 * @param DOMElement $item List item.
 */
function eichendorff_import_icon_item_text( DOMElement $item ): string {
	$xpath = new DOMXPath( $item->ownerDocument );
	$text  = $xpath->query( './/*[contains(@class, "elementor-icon-list-text")]', $item )->item( 0 );
	$html  = $text ? eichendorff_import_inner_html( $text ) : '';
	$link  = $xpath->query( './/a[@href]', $item )->item( 0 );

	return $link ? '<a href="' . esc_attr( $link->getAttribute( 'href' ) ) . "\">$html</a>" : $html;
}

/**
 * Turns the HTML of a text-editor widget into paragraph, heading and list blocks.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_text_editor( DOMElement $widget, string $page ): array {
	$blocks = array();
	$inline = '';

	$flush = static function () use ( &$blocks, &$inline ): void {
		$block = eichendorff_import_paragraph( $inline );
		if ( $block ) {
			$blocks[] = $block;
		}
		$inline = '';
	};

	foreach ( $widget->childNodes as $node ) {
		if ( ! $node instanceof DOMElement || in_array( $node->tagName, array( 'strong', 'b', 'em', 'i', 'a', 'span', 'br', 'u' ), true ) ) {
			$inline .= $widget->ownerDocument->saveHTML( $node );
			continue;
		}

		$flush();

		if ( 'p' === $node->tagName ) {
			$align = preg_match( '/text-align:\s*(left|center|right)/', $node->getAttribute( 'style' ), $m ) ? $m[1] : '';
			$block = eichendorff_import_paragraph( eichendorff_import_inner_html( $node ), $align );
		} elseif ( preg_match( '/^h([1-6])$/', $node->tagName, $m ) ) {
			$block = eichendorff_import_heading( (int) $m[1], eichendorff_import_inner_html( $node ) );
		} elseif ( in_array( $node->tagName, array( 'ul', 'ol' ), true ) ) {
			$block = eichendorff_import_list( $node, array(), 'eichendorff_import_list_item_text' );
		} else {
			throw new RuntimeException( "$page: znacznik <{$node->tagName}> w widgecie text-editor nie ma zamiany na blok." );
		}

		if ( $block ) {
			$blocks[] = $block;
		}
	}

	$flush();

	return $blocks;
}

/**
 * Turns one Elementor widget into blocks.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_widget( DOMElement $widget, string $page ): array {
	$type  = strtok( $widget->getAttribute( 'data-widget_type' ), '.' );
	$xpath = new DOMXPath( $widget->ownerDocument );

	switch ( $type ) {
		case 'heading':
			$title = $xpath->query( './/*[contains(@class, "elementor-heading-title")]', $widget )->item( 0 );
			return array( eichendorff_import_heading( (int) substr( $title->tagName, 1 ), eichendorff_import_inner_html( $title ) ) );

		case 'text-editor':
			return eichendorff_import_text_editor( $widget, $page );

		case 'icon-list':
			$list = $xpath->query( './/ul', $widget )->item( 0 );
			return array( eichendorff_import_list( $list, array( 'className' => 'is-style-square' ), 'eichendorff_import_icon_item_text' ) );

		case 'divider':
			return array( eichendorff_import_block( 'separator', array(), '<hr class="wp-block-separator has-alpha-channel-opacity"/>' ) );
	}

	throw new RuntimeException( "$page: widget `$type` nie ma zamiany na blok." );
}

/**
 * Tells whether serialized block markup is a paragraph.
 *
 * @param string $block Block markup.
 */
function eichendorff_import_is_paragraph( string $block ): bool {
	return str_starts_with( $block, '<!-- wp:paragraph' );
}

/**
 * Gives a paragraph the 16px top margin that separates Elementor widgets.
 *
 * @param string $block Paragraph block markup.
 */
function eichendorff_import_widget_gap( string $block ): string {
	$parsed = parse_blocks( $block )[0];
	$attrs  = $parsed['attrs'] + array( 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '1rem' ) ) ) );
	$html   = preg_replace( '/^<p( class="[^"]*")?>/', '<p$1 style="margin-top:1rem">', trim( $parsed['innerHTML'] ) );

	return eichendorff_import_block( 'paragraph', $attrs, $html );
}

/**
 * Builds block content from the rendered Elementor page.
 *
 * Only the first `h1` stays `h1`; later ones become `h3`, which looks the same on the page.
 * A page without `h1` starts with its title as `h1`.
 *
 * @param array  $source Production page.
 * @param string $page   Page path, for error messages.
 */
function eichendorff_import_content( array $source, string $page ): string {
	$document = new DOMDocument();
	libxml_use_internal_errors( true );
	$document->loadHTML( '<?xml encoding="utf-8"?><div>' . $source['content']['rendered'] . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$xpath   = new DOMXPath( $document );
	$widgets = $xpath->query( '//*[@data-widget_type][not(ancestor::*[@data-widget_type])]' );
	$blocks  = array();

	foreach ( $widgets as $widget ) {
		$widget_blocks = eichendorff_import_widget( $widget, $page );

		// Paragraphs of one text sit 14.4px apart; a paragraph from the next widget keeps the 16px widget gap.
		if ( $widget_blocks && $blocks && eichendorff_import_is_paragraph( end( $blocks ) ) && eichendorff_import_is_paragraph( $widget_blocks[0] ) ) {
			$widget_blocks[0] = eichendorff_import_widget_gap( $widget_blocks[0] );
		}

		array_push( $blocks, ...$widget_blocks );
	}

	$has_h1 = false;

	foreach ( $blocks as &$block ) {
		if ( ! str_starts_with( $block, '<!-- wp:heading {"level":1}' ) ) {
			continue;
		}

		if ( $has_h1 ) {
			$block = str_replace( array( '{"level":1}', '<h1 ', '</h1>' ), array( '{"level":3}', '<h3 ', '</h3>' ), $block );
		}

		$has_h1 = true;
	}
	unset( $block );

	if ( ! $has_h1 ) {
		array_unshift( $blocks, eichendorff_import_heading( 1, esc_html( html_entity_decode( $source['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) ) );
	}

	return implode( "\n\n", $blocks );
}

/**
 * Creates or updates the local copy of a production page and returns its ID.
 *
 * @param string $path  Page path.
 * @param array  $pages Production pages keyed by path.
 */
function eichendorff_import_page( string $path, array $pages ): int {
	if ( ! isset( $pages[ $path ] ) ) {
		throw new RuntimeException( "$path: nie ma takiej strony na produkcji." );
	}

	$source = $pages[ $path ];
	$parent = 0;

	if ( str_contains( $path, '/' ) ) {
		$parent_path = substr( $path, 0, strrpos( $path, '/' ) );
		$parent      = eichendorff_import_find_page( $pages[ $parent_path ]['id'] ?? 0 ) ?: eichendorff_import_page( $parent_path, $pages );
	}

	$id   = eichendorff_import_find_page( $source['id'] );
	$page = array(
		'ID'           => $id,
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_title'   => html_entity_decode( $source['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
		'post_name'    => $source['slug'],
		'post_parent'  => $parent,
		'menu_order'   => $source['menu_order'],
		'post_content' => eichendorff_import_content( $source, $path ),
	);

	$id = $id ? wp_update_post( wp_slash( $page ), true ) : wp_insert_post( wp_slash( $page ), true );

	if ( is_wp_error( $id ) ) {
		throw new RuntimeException( "$path: " . $id->get_error_message() );
	}

	update_post_meta( $id, '_eichendorff_source', "pl-page:{$source['id']}" );
	WP_CLI::log( "$path → $id" );

	return $id;
}

$eichendorff_pages  = eichendorff_import_source_pages();
$eichendorff_failed = 0;

foreach ( $args as $eichendorff_path ) {
	try {
		eichendorff_import_page( trim( $eichendorff_path, '/' ), $eichendorff_pages );
	} catch ( RuntimeException $eichendorff_error ) {
		WP_CLI::warning( $eichendorff_error->getMessage() );
		++$eichendorff_failed;
	}
}

if ( $eichendorff_failed ) {
	WP_CLI::error( "Nie zaimportowano stron: $eichendorff_failed." );
}

WP_CLI::success( 'Import zakończony.' );
