<?php
/**
 * estore.oasismena child theme.
 *
 * Mirrors the structure used on leminar-saudi: CDN Tailwind for layout, a Sass
 * colour map for brand tokens, CFS for page content, Customizer for site-wide
 * values. See CLAUDE.md at the project root for the full picture.
 */

/* -------------------------------------------------------------------------
 * Uploads: convert JPG/PNG to WebP on upload.
 * ---------------------------------------------------------------------- */
add_filter( 'wp_handle_upload', 'estore_convert_image_to_webp', 10, 2 );
// Sideloads (programmatic imports, media_sideload_image) go through a different
// filter, so register there too or imported files stay PNG/JPG.
add_filter( 'wp_handle_sideload', 'estore_convert_image_to_webp', 10, 2 );
function estore_convert_image_to_webp( $upload, $context ) {
	$allowed = array( 'image/jpeg', 'image/png' );
	if ( ! in_array( $upload['type'], $allowed, true ) || ! function_exists( 'imagewebp' ) ) {
		return $upload;
	}

	$file = $upload['file'];
	$img  = 'image/jpeg' === $upload['type'] ? @imagecreatefromjpeg( $file ) : @imagecreatefrompng( $file );
	if ( ! $img ) {
		return $upload;
	}

	if ( 'image/png' === $upload['type'] ) {
		imagepalettetotruecolor( $img );
		imagealphablending( $img, true );
		imagesavealpha( $img, true );
	}

	$webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );
	if ( imagewebp( $img, $webp, 85 ) ) {
		imagedestroy( $img );
		@unlink( $file );
		$upload['file'] = $webp;
		$upload['url']  = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $upload['url'] );
		$upload['type'] = 'image/webp';
	} else {
		imagedestroy( $img );
	}

	return $upload;
}

/* -------------------------------------------------------------------------
 * Theme setup.
 * ---------------------------------------------------------------------- */

// Page attributes (menu_order) on every public post type, so CPT items can be
// hand-ordered in the admin the way the design's numbered cards expect.
add_action( 'init', function () {
	foreach ( get_post_types( array( 'public' => true ), 'names' ) as $post_type ) {
		add_post_type_support( $post_type, 'page-attributes' );
	}
} );

// 'main-menu' comes from the BlankSlate parent; these are ours.
add_action( 'after_setup_theme', function () {
	register_nav_menus( array(
		'footer_menu' => __( 'Footer Menu (Get Involved)', 'estore-child' ),
		'legal_menu'  => __( 'Footer Menu (Privacy / Legal)', 'estore-child' ),
		'quick_links' => __( 'Quick Links', 'estore-child' ),
	) );
} );

/* -------------------------------------------------------------------------
 * Customizer: site-wide values read with get_theme_mod() in header/footer.
 * Nothing here is hardcoded to a host - logos default to empty and the
 * templates fall back to the site name.
 * ---------------------------------------------------------------------- */
add_action( 'customize_register', 'estore_customize_register' );
function estore_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'estore_theme_settings', array(
		'title'    => __( 'Theme Settings', 'estore-child' ),
		'priority' => 30,
	) );

	$fields = array(
		'header_logo'        => array( 'image',    __( 'Header Logo', 'estore-child' ) ),
		'footer_logo'        => array( 'image',    __( 'Footer Logo', 'estore-child' ) ),
		'footer_description' => array( 'textarea', __( 'Footer Description', 'estore-child' ) ),
		'footer_wordmark'    => array( 'text',     __( 'Footer Wordmark (large ghosted text)', 'estore-child' ) ),
		'newsletter_form_id' => array( 'text',     __( 'Newsletter CF7 Form ID', 'estore-child' ) ),
		'linkedin_link'      => array( 'url',      __( 'LinkedIn URL', 'estore-child' ) ),
		'instagram_link'     => array( 'url',      __( 'Instagram URL', 'estore-child' ) ),
		'facebook_link'      => array( 'url',      __( 'Facebook URL', 'estore-child' ) ),
		'x_link'             => array( 'url',      __( 'X URL', 'estore-child' ) ),
		'whatsapp_link'      => array( 'url',      __( 'WhatsApp URL', 'estore-child' ) ),
		'email_link'         => array( 'text',     __( 'Contact Email', 'estore-child' ) ),
		'phone_link'         => array( 'text',     __( 'Contact Phone', 'estore-child' ) ),
		'address_link'       => array( 'textarea', __( 'Address', 'estore-child' ) ),
	);

	foreach ( $fields as $key => list( $type, $label ) ) {
		$sanitize = 'url' === $type ? 'esc_url_raw' : ( 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field' );

		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => 'image' === $type ? 'esc_url_raw' : $sanitize,
			'transport'         => 'refresh',
		) );

		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, array(
				'label'   => $label,
				'section' => 'estore_theme_settings',
			) ) );
		} else {
			$wp_customize->add_control( $key, array(
				'label'   => $label,
				'section' => 'estore_theme_settings',
				'type'    => 'textarea' === $type ? 'textarea' : 'text',
			) );
		}
	}
}

/* -------------------------------------------------------------------------
 * Main menu walker. Emits Tailwind classes directly.
 * Unlike leminar-saudi this does NOT special-case a "Contact" item - the
 * contact CTA in header.php is rendered from the Customizer, so renaming a
 * menu item can never duplicate or hide a link.
 * ---------------------------------------------------------------------- */
class Estore_Nav_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$is_current = in_array( 'current-menu-item', (array) $item->classes, true )
			|| in_array( 'current-menu-ancestor', (array) $item->classes, true );

		$classes = $is_current
			? 'text-white after:w-full'
			: 'text-muted hover:text-white after:w-0 hover:after:w-full';

		$output .= sprintf(
			'<li class="relative"><a href="%1$s"%2$s class="nav-link %3$s">%4$s</a></li>',
			esc_url( $item->url ),
			$is_current ? ' aria-current="page"' : '',
			esc_attr( $classes ),
			esc_html( $item->title )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '';
	}
}

/* -------------------------------------------------------------------------
 * Quote requests.
 *
 * The design has no prices and no cart - every product CTA is "Request a
 * Quote". That is a contact form prefilled with the product, not commerce, so
 * there is deliberately no WooCommerce in this project.
 * ---------------------------------------------------------------------- */
function estore_quote_url( $product_id = 0 ) {
	$contact = get_page_by_path( 'contact' );
	$url     = $contact ? get_permalink( $contact ) : home_url( '/contact/' );

	if ( $product_id && get_post_type( $product_id ) === 'products' ) {
		$url = add_query_arg( 'product', rawurlencode( get_post_field( 'post_name', $product_id ) ), $url );
	}

	return $url;
}

/* -------------------------------------------------------------------------
 * Product category term fields.
 * CFS cannot attach fields to taxonomy terms, so the category image and badge
 * are plain term meta with controls on the term screens (same approach as
 * leminar-saudi, generalised).
 * ---------------------------------------------------------------------- */
function estore_term_fields() {
	return array(
		'category_image' => array( 'image', __( 'Category Image', 'estore-child' ), __( 'Used by the zig-zag band on the home page and the category hero.', 'estore-child' ) ),
		'category_badge' => array( 'text',  __( 'Category Badge', 'estore-child' ), __( 'Small pill over the image, e.g. FEATURED. Leave blank to hide.', 'estore-child' ) ),
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
