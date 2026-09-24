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
