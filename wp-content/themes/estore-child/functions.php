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
		'footer_menu' => __( 'Footer Menu', 'estore-child' ),
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
