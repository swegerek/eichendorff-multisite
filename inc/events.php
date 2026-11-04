<?php
/**
 * Events content type.
 *
 * @package Eichendorff
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the events post type and its date field.
 */
function eichendorff_register_events(): void {
	register_post_type(
		'calendar_event',
		array(
			'labels'              => array(
				'name'          => __( 'Wydarzenia', 'eichendorff' ),
				'singular_name' => __( 'Wydarzenie', 'eichendorff' ),
				'add_new_item'  => __( 'Dodaj wydarzenie', 'eichendorff' ),
				'edit_item'     => __( 'Edytuj wydarzenie', 'eichendorff' ),
				'all_items'     => __( 'Wszystkie wydarzenia', 'eichendorff' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'query_var'           => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'excerpt', 'custom-fields' ),
		)
	);

	register_post_meta(
		'calendar_event',
		'event_date',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'eichendorff_sanitize_event_date',
			'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
		)
	);

	register_block_bindings_source(
		'eichendorff/event-date',
		array(
			'label'              => __( 'Data wydarzenia', 'eichendorff' ),
			'get_value_callback' => 'eichendorff_event_date_binding',
			'uses_context'       => array( 'postId' ),
		)
	);
}
add_action( 'init', 'eichendorff_register_events' );

/**
 * Keeps only dates in the Y-m-d format.
 *
 * @param mixed $value Submitted date.
 */
function eichendorff_sanitize_event_date( mixed $value ): string {
	$value = (string) $value;
	$date  = DateTimeImmutable::createFromFormat( '!Y-m-d', $value );

	return $date && $date->format( 'Y-m-d' ) === $value ? $value : '';
}

/**
 * Returns part of the event date for a block binding.
 *
 * @param array    $source_args    Binding arguments.
 * @param WP_Block $block_instance Bound block.
 */
function eichendorff_event_date_binding( array $source_args, WP_Block $block_instance ): ?string {
	$date = get_post_meta( $block_instance->context['postId'] ?? 0, 'event_date', true );

	if ( ! $date ) {
		return null;
	}

	$timestamp = ( new DateTimeImmutable( $date, wp_timezone() ) )->getTimestamp();

	return wp_date( $source_args['format'] ?? 'j F Y', $timestamp );
}

/**
 * Adds the date field to the event edit screen and removes the Custom fields box.
 *
 * The event keeps `custom-fields` support, which the REST API needs to expose the date;
 * editors set the date in its own box, so the raw meta list is not shown.
 */
function eichendorff_add_event_date_box(): void {
	add_meta_box( 'eichendorff-event-date', __( 'Data wydarzenia', 'eichendorff' ), 'eichendorff_render_event_date_box', 'calendar_event', 'side', 'high' );
	remove_meta_box( 'postcustom', 'calendar_event', 'normal' );
}
add_action( 'add_meta_boxes_calendar_event', 'eichendorff_add_event_date_box' );

/**
 * Renders the date field.
 *
 * @param WP_Post $post Event.
 */
function eichendorff_render_event_date_box( WP_Post $post ): void {
	wp_nonce_field( 'eichendorff_event_date', 'eichendorff_event_date_nonce' );
	?>
	<label class="screen-reader-text" for="eichendorff-event-date-input"><?php esc_html_e( 'Data wydarzenia', 'eichendorff' ); ?></label>
	<input id="eichendorff-event-date-input" type="date" name="eichendorff_event_date" value="<?php echo esc_attr( get_post_meta( $post->ID, 'event_date', true ) ); ?>">
	<?php
}

/**
 * Saves the date field.
 *
 * @param int $post_id Event ID.
 */
function eichendorff_save_event_date( int $post_id ): void {
	$nonce = sanitize_text_field( wp_unslash( $_POST['eichendorff_event_date_nonce'] ?? '' ) );

	if ( ! wp_verify_nonce( $nonce, 'eichendorff_event_date' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, 'event_date', sanitize_text_field( wp_unslash( $_POST['eichendorff_event_date'] ?? '' ) ) );
}
add_action( 'save_post_calendar_event', 'eichendorff_save_event_date' );

/**
 * Sorts Query Loop blocks with events by event date and skips undated events.
 *
 * WordPress drops post types that are not viewable from the block query, so the type is set here.
 *
 * @param array    $query Query arguments.
 * @param WP_Block $block Query block.
 */
function eichendorff_events_query( array $query, WP_Block $block ): array {
	if ( 'calendar_event' !== ( $block->context['query']['postType'] ?? '' ) ) {
		return $query;
	}

	$query['post_type'] = 'calendar_event';

	return eichendorff_order_by_event_date( $query );
}
add_filter( 'query_loop_block_query_vars', 'eichendorff_events_query', 10, 2 );

/**
 * Orders events by event date and skips undated events.
 *
 * Also used for REST requests, which feed the Query Loop preview in the editor.
 *
 * @param array $query Query arguments.
 */
function eichendorff_order_by_event_date( array $query ): array {
	$query['meta_key']   = 'event_date';
	$query['orderby']    = 'meta_value';
	$query['order']      = 'DESC';
	$query['meta_query'] = array(
		array(
			'key'     => 'event_date',
			'value'   => '',
			'compare' => '!=',
		),
	);

	return $query;
}
add_filter( 'rest_calendar_event_query', 'eichendorff_order_by_event_date' );
