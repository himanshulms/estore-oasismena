<?php
/**
 * Setup.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
 * Newsletters (Tribulant) front-end assets.
 * ---------------------------------------------------------------------- */

// The plugin enqueues the whole of Bootstrap 5, plus Font Awesome, Select2 and
// a datepicker, on EVERY front-end page - not only where its form appears.
// Bootstrap's reboot re-spaces the nav and strips the hero buttons' fill, so
// it is dropped here; the footer subscribe form is styled by .newsletter-embed
// in style.scss instead. Admin screens keep the lot, so the plugin's own UI is
// untouched.
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() ) {
		return;
	}

	foreach ( array( 'newsletters-bootstrap', 'bootstrap-datepicker', 'fontawesome', 'select2', 'newsletters' ) as $handle ) {
		wp_dequeue_style( $handle );
	}

	foreach ( array( 'bootstrap', 'bootstrap-datepicker', 'bootstrap-datepicker-i18n', 'select2' ) as $handle ) {
		wp_dequeue_script( $handle );
	}
}, 100 );

/**
 * The Newsletters list the footer form subscribes to - the plugin's default
 * list. Returns 0 when the plugin is not installed, which hides the form.
 */
function estore_newsletter_list_id() {
	static $id = null;
	if ( null !== $id ) {
		return $id;
	}

	global $wpdb;
	$table = $wpdb->prefix . 'wpmlmailinglists';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) !== $table ) {
		$id = 0;
		return $id;
	}

	$id = (int) $wpdb->get_var( "SELECT id FROM {$table} WHERE `default` = 1 ORDER BY id ASC LIMIT 1" );
	if ( ! $id ) {
		$id = (int) $wpdb->get_var( "SELECT id FROM {$table} ORDER BY id ASC LIMIT 1" );
	}
	return $id;
}
