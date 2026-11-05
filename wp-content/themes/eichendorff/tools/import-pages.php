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

require_once __DIR__ . '/lib/media.php';
require_once __DIR__ . '/lib/import-landing.php';

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
	// Non-breaking spaces stay: on production they glue words together and change where lines wrap.
	$html = preg_replace( '#^([ \t\r\n]|<br\s*/?>)+|([ \t\r\n]|<br\s*/?>)+$#', '', $html );

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
 * Sets a paragraph in the 12px Extra small size.
 *
 * @param string $block Paragraph block markup.
 */
function eichendorff_import_small_text( string $block ): string {
	return preg_replace_callback(
		'/^<!-- wp:paragraph(?: (\{.*?\}))? -->\n<p( class="([^"]*)")?/',
		static function ( array $m ): string {
			$attrs = array( 'fontSize' => 'x-small' ) + ( json_decode( $m[1] ?? '', true ) ?: array() );
			$class = trim( ( $m[3] ?? '' ) . ' has-x-small-font-size' );

			return '<!-- wp:paragraph ' . wp_json_encode( $attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . " -->\n<p class=\"$class\"";
		},
		$block
	);
}

/**
 * Returns a heading block.
 *
 * @param int    $level      Heading level.
 * @param string $html       Inline HTML.
 * @param string $class_name Block style class, such as `is-style-title`.
 */
function eichendorff_import_heading( int $level, string $html, string $class_name = '' ): string {
	$attrs   = 2 === $level ? array() : array( 'level' => $level );
	$classes = 'wp-block-heading';

	if ( $class_name ) {
		$attrs['className'] = $class_name;
		$classes           .= " $class_name";
	}

	return eichendorff_import_block( 'heading', $attrs, "<h$level class=\"$classes\">" . eichendorff_import_inline( $html ) . "</h$level>" );
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
 * Returns the trimmed text of the first element matching an XPath query.
 *
 * @param string     $query   XPath query relative to the context node.
 * @param DOMElement $context Context node.
 */
function eichendorff_import_text( string $query, DOMElement $context ): string {
	$node = ( new DOMXPath( $context->ownerDocument ) )->query( $query, $context )->item( 0 );

	return $node ? trim( preg_replace( '/\s+/u', ' ', $node->textContent ) ) : '';
}

/**
 * Turns a nested-tabs widget into a Tabs block with one panel per tab.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_tabs( DOMElement $widget, string $page ): string {
	$xpath   = new DOMXPath( $widget->ownerDocument );
	$buttons = '';
	$panels  = array();

	foreach ( $xpath->query( './/*[@role="tab"]', $widget ) as $index => $tab ) {
		$label    = eichendorff_import_text( './/*[contains(@class, "e-n-tab-title-text")]', $tab );
		$panel    = $xpath->query( './/*[@role="tabpanel"]', $widget )->item( $index );
		$buttons .= '<button type="button" role="tab">' . esc_html( $label ) . '</button>';
		$panels[] = eichendorff_import_block( 'tab-panel', array( 'label' => $label ), '<section role="tabpanel" tabindex="0" class="wp-block-tab-panel">' . implode( "\n\n", eichendorff_import_widgets( $panel, $page ) ) . '</section>' );
	}

	$list = eichendorff_import_block( 'tab-list', array(), "<div role=\"tablist\" class=\"wp-block-tab-list\">$buttons</div>" );

	return "<!-- wp:tabs -->\n<div class=\"wp-block-tabs\">$list\n\n<!-- wp:tab-panels -->\n<div class=\"wp-block-tab-panels\">" . implode( "\n\n", $panels ) . "</div>\n<!-- /wp:tab-panels --></div>\n<!-- /wp:tabs -->";
}

/**
 * Turns a nested-accordion widget into an Accordion block with the icon on the left, as on production.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_accordion( DOMElement $widget, string $page ): string {
	$xpath = new DOMXPath( $widget->ownerDocument );
	$items = array();

	foreach ( $xpath->query( './/details', $widget ) as $details ) {
		$open    = $details->hasAttribute( 'open' );
		$attrs   = $open ? array( 'openByDefault' => true ) : array();
		$title   = esc_html( eichendorff_import_text( './/*[contains(@class, "e-n-accordion-item-title-text")]', $details ) );
		$region  = $xpath->query( './*[@role="region"]', $details )->item( 0 );
		$heading = eichendorff_import_block(
			'accordion-heading',
			$attrs + array( 'iconPosition' => 'left' ),
			'<h3 class="wp-block-accordion-heading has-icon has-icon-left"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span><span class="wp-block-accordion-heading__toggle-title">' . $title . '</span></button></h3>'
		);
		$panel   = eichendorff_import_block( 'accordion-panel', array(), '<div role="region" class="wp-block-accordion-panel">' . implode( "\n\n", $region ? eichendorff_import_widgets( $region, $page ) : array() ) . '</div>' );
		$items[] = eichendorff_import_block( 'accordion-item', $attrs, '<div class="wp-block-accordion-item' . ( $open ? ' is-open' : '' ) . "\">$heading\n\n$panel</div>" );
	}

	return eichendorff_import_block( 'accordion', array( 'iconPosition' => 'left' ), '<div role="group" class="wp-block-accordion">' . implode( "\n\n", $items ) . '</div>' );
}

/**
 * Turns the table of an HTML widget into a Table block with the Stripes style.
 *
 * @param DOMElement $table Table element.
 */
function eichendorff_import_table( DOMElement $table ): string {
	$xpath    = new DOMXPath( $table->ownerDocument );
	$sections = '';

	foreach ( array( 'thead', 'tbody' ) as $section ) {
		$rows = '';

		foreach ( $xpath->query( ".//$section/tr", $table ) as $row ) {
			$rows .= '<tr>';

			foreach ( $row->childNodes as $cell ) {
				if ( ! $cell instanceof DOMElement ) {
					continue;
				}

				$colspan = $cell->hasAttribute( 'colspan' ) ? ' colspan="' . (int) $cell->getAttribute( 'colspan' ) . '"' : '';
				$rows   .= "<{$cell->tagName}$colspan>" . eichendorff_import_inline( eichendorff_import_inner_html( $cell ) ) . "</{$cell->tagName}>";
			}

			$rows .= '</tr>';
		}

		$sections .= $rows ? "<$section>$rows</$section>" : '';
	}

	return eichendorff_import_block(
		'table',
		array(
			'hasFixedLayout' => false,
			'className'      => 'is-style-stripes',
		),
		"<figure class=\"wp-block-table is-style-stripes\"><table>$sections</table></figure>"
	);
}

/**
 * Turns a gallery widget into a Gallery block with the Grid style and images that open in the lightbox.
 *
 * The full files from production go to the media library; the grid shows the medium_large size.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_gallery( DOMElement $widget, string $page ): string {
	$images = array();

	foreach ( ( new DOMXPath( $widget->ownerDocument ) )->query( './/a[contains(@class, "e-gallery-item")]', $widget ) as $item ) {
		$id = eichendorff_media_from_url( $item->getAttribute( 'href' ), 0, $item->getAttribute( 'data-elementor-lightbox-title' ) );

		if ( ! $id ) {
			throw new RuntimeException( "$page: nie udało się pobrać zdjęcia {$item->getAttribute( 'href' )}." );
		}

		$attrs    = array(
			'lightbox'        => array( 'enabled' => true ),
			'id'              => $id,
			'sizeSlug'        => 'medium_large',
			'linkDestination' => 'none',
		);
		$src      = esc_url( wp_get_attachment_image_url( $id, 'medium_large' ) );
		$alt      = esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) );
		$images[] = eichendorff_import_block( 'image', $attrs, "<figure class=\"wp-block-image size-medium_large\"><img src=\"$src\" alt=\"$alt\" class=\"wp-image-$id\"/></figure>" );
	}

	return eichendorff_import_block(
		'gallery',
		array(
			'linkTo'    => 'none',
			'sizeSlug'  => 'medium_large',
			'className' => 'is-style-grid',
		),
		'<figure class="wp-block-gallery has-nested-images columns-default is-cropped is-style-grid">' . implode( "\n\n", $images ) . '</figure>'
	);
}

/**
 * Turns a video widget into an Embed block for YouTube, or a Video block for a file.
 *
 * A file from production up to EICHENDORFF_MAX_MEDIA_BYTES goes to the media library;
 * a larger one, or one from elsewhere, plays from its full address.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages and relative addresses.
 */
function eichendorff_import_video( DOMElement $widget, string $page ): string {
	$settings = json_decode( $widget->getAttribute( 'data-settings' ), true ) ?: array();
	$xpath    = new DOMXPath( $widget->ownerDocument );

	if ( 'youtube' === ( $settings['video_type'] ?? '' ) ) {
		$url    = $settings['youtube_url'] ?? '';
		$iframe = $xpath->query( './/iframe[contains(@src, "youtube.com/embed/")]', $widget )->item( 0 );

		if ( ! $url && $iframe && preg_match( '#youtube\.com/embed/([\w-]+)#', $iframe->getAttribute( 'src' ), $m ) ) {
			$url = "https://www.youtube.com/watch?v={$m[1]}";
		}

		if ( ! preg_match( '#^https://(www\.)?(youtube\.com/watch\?v=|youtu\.be/)[\w-]+#', $url ) ) {
			throw new RuntimeException( "$page: film z YouTube bez adresu." );
		}

		return eichendorff_import_block(
			'embed',
			array(
				'url'              => $url,
				'type'             => 'video',
				'providerNameSlug' => 'youtube',
				'responsive'       => true,
				'className'        => 'wp-embed-aspect-16-9 wp-has-aspect-ratio',
			),
			"<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\n$url\n</div></figure>"
		);
	}

	$video = $xpath->query( './/video[@src]', $widget )->item( 0 );

	if ( ! $video ) {
		throw new RuntimeException( "$page: widget wideo bez pliku." );
	}

	$src   = WP_Http::make_absolute_url( $video->getAttribute( 'src' ), EICHENDORFF_SOURCE_SITE . "/$page/" );
	$attrs = array();

	if ( str_starts_with( $src, EICHENDORFF_SOURCE_SITE . '/' ) ) {
		$size = (int) wp_remote_retrieve_header( wp_remote_head( $src, array( 'timeout' => 30 ) ), 'content-length' );

		if ( $size && $size <= EICHENDORFF_MAX_MEDIA_BYTES ) {
			$id = eichendorff_media_from_url( $src, 0 );

			if ( ! $id ) {
				throw new RuntimeException( "$page: nie udało się pobrać filmu $src." );
			}

			$attrs['id'] = $id;
			$src         = wp_get_attachment_url( $id );
		}
	}

	$poster = '';

	if ( $video->getAttribute( 'poster' ) ) {
		$poster_id = eichendorff_media_from_url( $video->getAttribute( 'poster' ), 0 );
		$poster    = $poster_id ? ' poster="' . esc_url( wp_get_attachment_url( $poster_id ) ) . '"' : '';
	}

	// Without a poster production loads nothing before the video starts; with one it loads the metadata, the block default.
	$preload = 'none' === $video->getAttribute( 'preload' ) ? ' preload="none"' : '';

	return eichendorff_import_block( 'video', $attrs, '<figure class="wp-block-video"><video controls' . $poster . $preload . ' src="' . esc_url( $src ) . '"></video></figure>' );
}

/**
 * Turns a Google Maps widget into a Custom HTML block with the same map as production.
 *
 * Google Maps has no oEmbed, so there is no core block for it.
 *
 * @param DOMElement $widget Widget element.
 * @param string     $page   Page path, for error messages.
 */
function eichendorff_import_map( DOMElement $widget, string $page ): string {
	$iframe = ( new DOMXPath( $widget->ownerDocument ) )->query( './/iframe', $widget )->item( 0 );

	if ( ! $iframe ) {
		throw new RuntimeException( "$page: widget mapy bez mapy." );
	}

	return eichendorff_import_block(
		'html',
		array(),
		sprintf( '<iframe class="map-embed" src="%s" title="%s" loading="lazy"></iframe>', esc_url( $iframe->getAttribute( 'src' ) ), esc_attr( $iframe->getAttribute( 'title' ) ) )
	);
}

/**
 * Turns an Elementor grid of containers into a Video grid block, one Film block per container.
 *
 * A Film holds a title and a player, so each container must give a heading followed by a video or an embed.
 *
 * @param DOMElement $grid Grid container.
 * @param string     $page Page path, for error messages.
 */
function eichendorff_import_grid( DOMElement $grid, string $page ): string {
	$films = array();

	foreach ( $grid->childNodes as $child ) {
		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		$blocks = 'container' === $child->getAttribute( 'data-e-type' ) ? eichendorff_import_widgets( $child, $page ) : array();

		if ( 2 !== count( $blocks ) || ! str_starts_with( $blocks[0], '<!-- wp:heading' ) || ! preg_match( '/^<!-- wp:(video|embed)[ -]/', $blocks[1] ) ) {
			throw new RuntimeException( "$page: element siatki to nie tytuł z filmem." );
		}

		$films[] = eichendorff_import_block( 'eichendorff/film', array(), "<div class=\"wp-block-eichendorff-film\">\n" . implode( "\n\n", $blocks ) . "\n</div>" );
	}

	return eichendorff_import_block( 'eichendorff/video-grid', array(), "<div class=\"wp-block-eichendorff-video-grid\">\n" . implode( "\n\n", $films ) . "\n</div>" );
}

/**
 * Tells whether the last thing in a widget is a paragraph, and not bare text or another element.
 *
 * @param DOMElement $widget Widget element.
 */
function eichendorff_import_ends_with_paragraph( DOMElement $widget ): bool {
	for ( $node = $widget->lastChild; $node; $node = $node->previousSibling ) {
		if ( $node instanceof DOMText && '' === trim( $node->textContent ) ) {
			continue;
		}

		return $node instanceof DOMElement && 'p' === $node->tagName;
	}

	return false;
}

/**
 * Returns the Elementor element ID of a widget or container.
 *
 * @param DOMElement $element Element.
 */
function eichendorff_import_element_id( DOMElement $element ): string {
	return preg_match( '/elementor-element-([0-9a-f]+)/', $element->getAttribute( 'class' ), $m ) ? $m[1] : '';
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

	// Atomic Elementor widgets have no widget type; a heading is the element itself.
	if ( ! $type && preg_match( '/^h([1-6])$/', $widget->tagName, $m ) ) {
		$attrs = array(
			'style'    => array( 'typography' => array( 'fontWeight' => '700', 'lineHeight' => '1.2' ) ),
			'fontSize' => 'heading',
		);

		if ( '2' !== $m[1] ) {
			$attrs = array( 'level' => (int) $m[1] ) + $attrs;
		}

		$html = "<h{$m[1]} class=\"wp-block-heading has-heading-font-size\" style=\"font-weight:700;line-height:1.2\">" . eichendorff_import_inline( eichendorff_import_inner_html( $widget ) ) . "</h{$m[1]}>";

		return array( eichendorff_import_block( 'heading', $attrs, $html ) );
	}

	switch ( $type ) {
		case 'nested-tabs':
			return array( eichendorff_import_tabs( $widget, $page ) );

		case 'nested-accordion':
			return array( eichendorff_import_accordion( $widget, $page ) );

		case 'gallery':
			return array( eichendorff_import_gallery( $widget, $page ) );

		case 'html':
			$tables = $xpath->query( './/table', $widget );
			if ( 1 === $tables->length && '' === trim( str_replace( $tables->item( 0 )->textContent, '', $widget->textContent ) ) ) {
				return array( eichendorff_import_table( $tables->item( 0 ) ) );
			}
			break;

		case 'heading':
			$title = $xpath->query( './/*[contains(@class, "elementor-heading-title")]', $widget )->item( 0 );
			$level = (int) substr( $title->tagName, 1 );
			$style = $level > 1 && in_array( eichendorff_import_element_id( $widget ), $GLOBALS['eichendorff_title_headings'] ?? array(), true ) ? 'is-style-title' : '';
			return array( eichendorff_import_heading( $level, eichendorff_import_inner_html( $title ), $style ) );

		case 'icon':
			return array( eichendorff_import_void_block( 'icon', array( 'icon' => eichendorff_import_icon_name( $xpath->query( './/svg', $widget )->item( 0 ), $page ) ) ) );

		case 'image':
			return array( eichendorff_import_image( $widget, $page ) );

		case 'video':
			return array( eichendorff_import_video( $widget, $page ) );

		case 'google_maps':
			return array( eichendorff_import_map( $widget, $page ) );

		case 'text-editor':
			$blocks = eichendorff_import_text_editor( $widget, $page );

			// On landing pages a text can be set in the 12px size.
			if ( in_array( eichendorff_import_element_id( $widget ), $GLOBALS['eichendorff_small_texts'] ?? array(), true ) ) {
				$blocks = array_map( 'eichendorff_import_small_text', $blocks );
			}

			return $blocks;

		case 'icon-list':
			if ( ! empty( $GLOBALS['eichendorff_landing'] ) ) {
				return array( eichendorff_import_icon_row( $widget, $page ) );
			}

			$list  = $xpath->query( './/ul', $widget )->item( 0 );
			$style = in_array( eichendorff_import_element_id( $widget ), $GLOBALS['eichendorff_column_lists'] ?? array(), true ) ? 'is-style-square-columns' : 'is-style-square';
			return array( eichendorff_import_list( $list, array( 'className' => $style ), 'eichendorff_import_icon_item_text' ) );

		case 'divider':
			return array( eichendorff_import_block( 'separator', array(), '<hr class="wp-block-separator has-alpha-channel-opacity"/>' ) );
	}

	$name = $type ? $type : $widget->tagName;

	throw new RuntimeException( "$page: widget `$name` nie ma zamiany na blok." );
}

/**
 * Turns the widgets of a container into blocks, skipping widgets nested in other widgets.
 *
 * A grid container becomes one block with its widgets inside.
 *
 * @param DOMElement $root Page, tab or accordion panel.
 * @param string     $page Page path, for error messages.
 */
function eichendorff_import_widgets( DOMElement $root, string $page ): array {
	$is_widget = static fn( DOMNode $node ): bool => $node instanceof DOMElement && ( $node->hasAttribute( 'data-widget_type' ) || 'widget' === $node->getAttribute( 'data-e-type' ) );
	$is_grid   = static fn( DOMNode $node ): bool => $node instanceof DOMElement && 'container' === $node->getAttribute( 'data-e-type' ) && in_array( 'e-grid', explode( ' ', $node->getAttribute( 'class' ) ), true );
	$is_row    = static fn( DOMNode $node ): bool => $node instanceof DOMElement && 'container' === $node->getAttribute( 'data-e-type' ) && eichendorff_import_children( $node ) && ! array_filter( eichendorff_import_children( $node ), static fn( DOMElement $child ): bool => 'button.default' !== $child->getAttribute( 'data-widget_type' ) );
	$blocks    = array();
	$keeps_gap = false;

	foreach ( iterator_to_array( $root->getElementsByTagName( '*' ) ) as $widget ) {
		if ( ! $is_widget( $widget ) && ! $is_grid( $widget ) && ! $is_row( $widget ) ) {
			continue;
		}

		for ( $parent = $widget->parentNode; $parent && $parent !== $root; $parent = $parent->parentNode ) {
			if ( $is_widget( $parent ) || $is_grid( $parent ) || $is_row( $parent ) ) {
				continue 2;
			}
		}

		if ( $is_row( $widget ) ) {
			$widget_blocks = array( eichendorff_import_buttons( $widget, $page ) );
		} elseif ( $is_grid( $widget ) ) {
			$widget_blocks = array( eichendorff_import_grid( $widget, $page ) );
		} else {
			$widget_blocks = eichendorff_import_widget( $widget, $page );
		}

		// A text without the `f-text-last-mb-0` class keeps the 14.4px margin of its last paragraph above the next widget.
		if ( $widget_blocks && $keeps_gap && eichendorff_import_is_paragraph( end( $blocks ) ) ) {
			$blocks[ array_key_last( $blocks ) ] = eichendorff_import_spacing( end( $blocks ), array( 'padding' => array( 'bottom' => '0.9rem' ) ) );
		}

		// Within one text a paragraph or list follows a paragraph 14.4px below; the next widget keeps the 16px widget gap.
		if ( $widget_blocks && $blocks && eichendorff_import_is_paragraph( end( $blocks ) ) && eichendorff_import_is_text( $widget_blocks[0] ) ) {
			$widget_blocks[0] = eichendorff_import_spacing( $widget_blocks[0], array( 'margin' => array( 'top' => '1rem' ) ) );
		}

		$keeps_gap = $widget_blocks && 'text-editor.default' === $widget->getAttribute( 'data-widget_type' ) && ! in_array( 'f-text-last-mb-0', explode( ' ', $widget->getAttribute( 'class' ) ), true ) && eichendorff_import_ends_with_paragraph( $widget );

		array_push( $blocks, ...$widget_blocks );
	}

	return $blocks;
}

/**
 * Returns the Elementor CSS of a production page, kept next to the copies of the pages.
 *
 * @param int $source_id Production page ID.
 */
function eichendorff_import_page_css( int $source_id ): string {
	$file = eichendorff_import_dir() . "/$source_id.css";

	if ( ! file_exists( $file ) ) {
		$response = wp_remote_get( EICHENDORFF_SOURCE_SITE . "/wp-content/uploads/elementor/css/post-$source_id.css", array( 'timeout' => 60 ) );
		file_put_contents( $file, 200 === wp_remote_retrieve_response_code( $response ) ? wp_remote_retrieve_body( $response ) : '' );
	}

	return file_get_contents( $file );
}

/**
 * Returns the Elementor element IDs of icon lists laid out in columns by custom CSS on production.
 *
 * @param int $source_id Production page ID.
 */
function eichendorff_import_column_lists( int $source_id ): array {
	preg_match_all( '/elementor-element-([0-9a-f]+) ul\s*\{\s*column-count:\s*[2-9]/', eichendorff_import_page_css( $source_id ), $m );

	return array_unique( $m[1] );
}

/**
 * Returns the Elementor element IDs of headings set in the page title typography on production.
 *
 * @param int $source_id Production page ID.
 */
function eichendorff_import_title_headings( int $source_id ): array {
	preg_match_all( '/elementor-element-([0-9a-f]+) \.elementor-heading-title\s*\{\s*font-family:\s*var\(\s*--e-global-typography-primary-/', eichendorff_import_page_css( $source_id ), $m );

	return array_unique( $m[1] );
}

/**
 * Adds the PDF files a page links to on production to the media library and links to the local copies.
 *
 * @param string $html Rendered page.
 */
function eichendorff_import_files( string $html ): string {
	return preg_replace_callback(
		'#href="(' . preg_quote( EICHENDORFF_SOURCE_SITE, '#' ) . '/[^"]+\.pdf)"#i',
		static function ( array $m ): string {
			$attachment = eichendorff_media_from_url( html_entity_decode( $m[1] ), 0 );

			return $attachment ? 'href="' . esc_url( wp_get_attachment_url( $attachment ) ) . '"' : $m[0];
		},
		$html
	);
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
 * Tells whether serialized block markup is a paragraph or a plain list, the blocks of a text widget.
 *
 * @param string $block Block markup.
 */
function eichendorff_import_is_text( string $block ): bool {
	return eichendorff_import_is_paragraph( $block ) || ( str_starts_with( $block, '<!-- wp:list' ) && ! str_contains( strtok( $block, "\n" ), 'is-style-square' ) );
}

/**
 * Adds spacing to a paragraph or list, such as the 16px top margin that separates Elementor widgets.
 *
 * @param string $block   Paragraph or list block markup.
 * @param array  $spacing Spacing attributes, such as `[ 'margin' => [ 'top' => '1rem' ] ]`.
 */
function eichendorff_import_spacing( string $block, array $spacing ): string {
	return preg_replace_callback(
		'/^<!-- wp:(paragraph|list)(?: (\{.*?\}))? -->\n<(p|ul|ol)( class="[^"]*")?(?: style="[^"]*")?>/',
		static function ( array $m ) use ( $spacing ): string {
			$attrs = array_replace_recursive( json_decode( $m[2] ?? '', true ) ?: array(), array( 'style' => array( 'spacing' => $spacing ) ) );
			$css   = array();

			foreach ( array( 'padding', 'margin' ) as $property ) {
				foreach ( $attrs['style']['spacing'][ $property ] ?? array() as $side => $value ) {
					$css[] = "$property-$side:$value";
				}
			}

			return "<!-- wp:{$m[1]} " . wp_json_encode( $attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . " -->\n<{$m[3]}" . ( $m[4] ?? '' ) . ' style="' . implode( ';', $css ) . '">';
		},
		$block
	);
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
	$document->loadHTML( '<?xml encoding="utf-8"?><div>' . eichendorff_import_files( $source['content']['rendered'] ) . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$GLOBALS['eichendorff_column_lists']   = eichendorff_import_column_lists( $source['id'] );
	$GLOBALS['eichendorff_title_headings'] = eichendorff_import_title_headings( $source['id'] );
	$GLOBALS['eichendorff_landing']        = in_array( $page, EICHENDORFF_LANDING_PAGES, true );
	$GLOBALS['eichendorff_small_texts']    = array();

	if ( $GLOBALS['eichendorff_landing'] ) {
		preg_match_all( '/elementor-element-([0-9a-f]+)\{[^}]*font-size:0\.75rem/', eichendorff_import_page_css( $source['id'] ), $m );
		$GLOBALS['eichendorff_small_texts'] = $m[1];

		return implode( "\n\n", eichendorff_import_landing( $document->documentElement, $source, $page ) );
	}

	$blocks = eichendorff_import_widgets( $document->documentElement, $page );
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

	if ( in_array( $path, EICHENDORFF_LANDING_PAGES, true ) ) {
		update_post_meta( $id, '_wp_page_template', 'page-landing' );
	}
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
