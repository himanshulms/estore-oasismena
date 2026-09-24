<?php
/**
 * Term Fields.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Product category term fields.
 * CFS cannot attach fields to taxonomy terms, so the category image and badge
 * are plain term meta with controls on the term screens (same approach as
 * leminar-saudi, generalised).
 * ---------------------------------------------------------------------- */
function estore_term_fields() {
	return array(
		'category_image'      => array( 'image', __( 'Category Image', 'estore-child' ), __( 'Square image for the zig-zag band on the home page.', 'estore-child' ) ),
		'category_hero_image' => array( 'image', __( 'Category Hero Image', 'estore-child' ), __( 'Wide 3:2 image for the product detail and category archive heroes. Falls back to the Category Image, which is square and will crop.', 'estore-child' ) ),
		'category_badge'      => array( 'text',  __( 'Category Badge', 'estore-child' ), __( 'Small pill over the image, e.g. FEATURED. Leave blank to hide.', 'estore-child' ) ),
	);
}

function estore_term_field_control( $key, $type, $value ) {
	if ( 'image' === $type ) {
		printf(
			'<input type="hidden" name="%1$s" id="%1$s" value="%2$s" class="estore-media-value">'
			. '<img src="%2$s" class="estore-media-preview" style="%3$smax-width:180px;height:auto;margin-bottom:8px;border:1px solid #dcdcde;padding:4px;background:#fff;">'
			. '<button type="button" class="button estore-media-pick">%4$s</button> '
			. '<button type="button" class="button estore-media-clear" style="%5$s">%6$s</button>',
			esc_attr( $key ), esc_url( $value ),
			$value ? 'display:block;' : 'display:none;',
			esc_html__( 'Select Image', 'estore-child' ),
			$value ? '' : 'display:none;',
			esc_html__( 'Remove', 'estore-child' )
		);
		return;
	}
	printf( '<input type="text" name="%1$s" id="%1$s" value="%2$s" class="regular-text">', esc_attr( $key ), esc_attr( $value ) );
}

add_action( 'product-category_add_form_fields', function () {
	foreach ( estore_term_fields() as $key => list( $type, $label, $help ) ) {
		echo '<div class="form-field"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
		estore_term_field_control( $key, $type, '' );
		echo '<p>' . esc_html( $help ) . '</p></div>';
	}
} );

add_action( 'product-category_edit_form_fields', function ( $term ) {
	foreach ( estore_term_fields() as $key => list( $type, $label, $help ) ) {
		echo '<tr class="form-field"><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
		estore_term_field_control( $key, $type, (string) get_term_meta( $term->term_id, $key, true ) );
		echo '<p class="description">' . esc_html( $help ) . '</p></td></tr>';
	}
} );

add_action( 'created_product-category', 'estore_save_term_fields' );
add_action( 'edited_product-category', 'estore_save_term_fields' );
function estore_save_term_fields( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	foreach ( estore_term_fields() as $key => list( $type ) ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		update_term_meta( $term_id, $key, 'image' === $type ? esc_url_raw( $raw ) : sanitize_text_field( $raw ) );
	}
}

/** Media modal for the image fields on the product-category screens. */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) || ( $_GET['taxonomy'] ?? '' ) !== 'product-category' ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_script( 'jquery-core', <<<'JS'
jQuery(function ($) {
    $(document).on('click', '.estore-media-pick', function (e) {
        e.preventDefault();
        var $wrap = $(this).closest('td, .form-field');
        var frame = wp.media({ title: 'Select Image', multiple: false, library: { type: 'image' } });
        frame.on('select', function () {
            var url = frame.state().get('selection').first().toJSON().url;
            $wrap.find('.estore-media-value').val(url);
            $wrap.find('.estore-media-preview').attr('src', url).show();
            $wrap.find('.estore-media-clear').show();
        });
        frame.open();
    });
    $(document).on('click', '.estore-media-clear', function (e) {
        e.preventDefault();
        var $wrap = $(this).closest('td, .form-field');
        $wrap.find('.estore-media-value').val('');
        $wrap.find('.estore-media-preview').hide();
        $(this).hide();
    });
});
JS
	);
} );
