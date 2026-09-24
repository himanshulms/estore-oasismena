<?php
/**
 * Customizer.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		// Repeated product-page labels, so wording is editable without code.
		'quote_label'        => array( 'text',     __( 'Quote Button Label', 'estore-child' ) ),
		'description_label'  => array( 'text',     __( 'Product "Description:" Label', 'estore-child' ) ),
		'related_eyebrow'    => array( 'text',     __( 'Related Products Eyebrow', 'estore-child' ) ),
		'related_heading'    => array( 'text',     __( 'Related Products Heading', 'estore-child' ) ),
		'catalogue_eyebrow'  => array( 'text',     __( 'Products Page Eyebrow', 'estore-child' ) ),
		'catalogue_heading'  => array( 'text',     __( 'Products Page Heading', 'estore-child' ) ),
		'all_categories'     => array( 'text',     __( 'All-Categories Option Label', 'estore-child' ) ),
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
