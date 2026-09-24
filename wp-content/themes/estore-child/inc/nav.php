<?php
/**
 * Nav.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
