<?php
/**
 * Catalogue.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Quote requests.
 *
 * The design has no prices and no cart - every product CTA is "Request a
 * Quote". That is a contact form prefilled with the product, not commerce, so
 * there is deliberately no WooCommerce in this project.
 * ---------------------------------------------------------------------- */
/** Editable label for every "Request a Quote" control. */
function estore_quote_label() {
	$label = trim( (string) get_theme_mod( 'quote_label' ) );
	return $label ?: __( 'Request a Quote', 'estore-child' );
}

/** Small helper so templates can read a Customizer label with a fallback. */
function estore_label( $key, $default ) {
	$value = trim( (string) get_theme_mod( $key ) );
	return $value ?: $default;
}

/**
 * product-category is hierarchical: three top-level categories, each with its
 * brands as child terms. Most of the UI wants the categories only.
 */
function estore_top_categories() {
	$terms = get_terms( array(
		'taxonomy'   => 'product-category',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();
}

/** The brands filed under one category. */
function estore_category_brands( $term_id ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product-category',
		'hide_empty' => false,
		'parent'     => (int) $term_id,
	) );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();
}

/**
 * A product is filed under a brand, so walk up to the category it belongs to.
 * Falls back to the term itself when a product is filed directly on a category.
 */
function estore_product_category( $product_id ) {
	$terms = get_the_terms( $product_id, 'product-category' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	foreach ( $terms as $term ) {
		if ( 0 === (int) $term->parent ) {
			return $term;
		}
	}
	$first  = reset( $terms );
	$parent = $first->parent ? get_term( $first->parent, 'product-category' ) : $first;
	return ( $parent && ! is_wp_error( $parent ) ) ? $parent : $first;
}

/** The brand a product is filed under, if any. */
function estore_product_brand( $product_id ) {
	$terms = get_the_terms( $product_id, 'product-category' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	foreach ( $terms as $term ) {
		if ( (int) $term->parent > 0 ) {
			return $term;
		}
	}
	return null;
}

/** Wide hero image for a category, falling back to the square band image. */
function estore_category_hero( $term_id ) {
	// Walk up: this term's own hero, then its square band image, then repeat on
	// its parent. A brand can therefore override its category's hero, and a
	// brand with no imagery of its own inherits one.
	$seen = 0;
	while ( $term_id && $seen++ < 5 ) {
		$hero = trim( (string) get_term_meta( $term_id, 'category_hero_image', true ) );
		if ( $hero ) {
			return $hero;
		}
		$img = trim( (string) get_term_meta( $term_id, 'category_image', true ) );
		if ( $img ) {
			return $img;
		}
		$term    = get_term( $term_id, 'product-category' );
		$term_id = ( $term && ! is_wp_error( $term ) ) ? (int) $term->parent : 0;
	}
	return '';
}

function estore_quote_url( $product_id = 0 ) {
	$contact = get_page_by_path( 'contact' );
	$url     = $contact ? get_permalink( $contact ) : home_url( '/contact/' );

	if ( $product_id && get_post_type( $product_id ) === 'products' ) {
		$url = add_query_arg( 'product', rawurlencode( get_post_field( 'post_name', $product_id ) ), $url );
	}

	return $url;
}
