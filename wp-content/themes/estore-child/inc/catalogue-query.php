<?php
/**
 * Catalogue Query.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Product grid + AJAX filter.
 *
 * One renderer shared by templates/products.php and the AJAX callback, so the
 * initial page load and a filtered response can never drift apart.
 * ---------------------------------------------------------------------- */
function estore_render_product_grid( $term_id = 0, $limit = -1, $exclude = 0 ) {
	$args = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	);

	if ( $term_id ) {
		$args['tax_query'] = array( array(
			'taxonomy' => 'product-category',
			'field'    => 'term_id',
			'terms'    => (int) $term_id,
		) );
	}
	if ( $exclude ) {
		$args['post__not_in'] = array( (int) $exclude );
	}

	$q = new WP_Query( $args );

	if ( ! $q->have_posts() ) {
		echo '<p class="col-span-full text-center text-muted py-12">'
			. esc_html__( 'No products found in this category.', 'estore-child' ) . '</p>';
		return;
	}

	while ( $q->have_posts() ) {
		$q->the_post();
		get_template_part( 'template-parts/product-card' );
	}
	wp_reset_postdata();
}

// Registered for both logged-in and logged-out visitors - the filter is public.
add_action( 'wp_ajax_estore_filter_products', 'estore_filter_products' );
add_action( 'wp_ajax_nopriv_estore_filter_products', 'estore_filter_products' );
function estore_filter_products() {
	check_ajax_referer( 'estore_products', 'nonce' );

	$term_id = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
	if ( $term_id && ! term_exists( $term_id, 'product-category' ) ) {
		wp_send_json_error( 'Unknown category.', 400 );
	}

	ob_start();
	estore_render_product_grid( $term_id );
	wp_send_json_success( array( 'html' => ob_get_clean() ) );
}

/** "Top rated" flag, shown as a checkbox on the product edit screen. */
add_action( 'add_meta_boxes_products', function () {
	add_meta_box( 'estore_top_rated', __( 'Home Page', 'estore-child' ), function ( $post ) {
		wp_nonce_field( 'estore_top_rated_save', 'estore_top_rated_nonce' );
		$on = get_post_meta( $post->ID, '_estore_top_rated', true );
		echo '<label><input type="checkbox" name="estore_top_rated" value="1" ' . checked( $on, '1', false ) . '> '
			. esc_html__( 'Feature in the Top Rated row', 'estore-child' ) . '</label>';
	}, 'products', 'side' );
} );

add_action( 'save_post_products', function ( $post_id ) {
	if ( ! isset( $_POST['estore_top_rated_nonce'] ) || ! wp_verify_nonce( $_POST['estore_top_rated_nonce'], 'estore_top_rated_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['estore_top_rated'] ) ) {
		update_post_meta( $post_id, '_estore_top_rated', '1' );
	} else {
		delete_post_meta( $post_id, '_estore_top_rated' );
	}
} );
