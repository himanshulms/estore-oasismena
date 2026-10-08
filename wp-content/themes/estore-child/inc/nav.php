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
 *
 * Unlike leminar-saudi this does NOT special-case a "Contact" item - the
 * contact CTA in header.php is rendered from the Customizer, so renaming a
 * menu item can never duplicate or hide a link.
 *
 * A top-level item with children gets a caret button and a panel styled like
 * the category dropdown on the Products page, so the two read as the same
 * control. Children are plain links; only one level deep is supported, which
 * is all the menu needs.
 * ---------------------------------------------------------------------- */
class Estore_Nav_Walker extends Walker_Nav_Menu {

	/** The submenu panel. */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="nav-dropdown" hidden>';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$is_current = in_array( 'current-menu-item', (array) $item->classes, true )
			|| in_array( 'current-menu-ancestor', (array) $item->classes, true );

		// Children: quieter rows inside the panel.
		if ( $depth > 0 ) {
			$output .= sprintf(
				'<li><a href="%1$s"%2$s class="nav-dropdown__option%3$s">%4$s</a></li>',
				esc_url( $item->url ),
				$is_current ? ' aria-current="page"' : '',
				$is_current ? ' is-active' : '',
				esc_html( $item->title )
			);
			return;
		}

		$classes = $is_current
			? 'text-white after:w-full'
			: 'text-muted hover:text-white after:w-0 hover:after:w-full';

		// WordPress sets $this->has_children on the walker; it only mirrors it
		// onto $args when $args is an array, which wp_nav_menu never passes. The
		// $args fallback keeps this working on older cores.
		$has_children = ! empty( $this->has_children ) || ! empty( $args->has_children );

		$output .= '<li class="relative' . ( $has_children ? ' nav-item--has-children' : '' ) . '">';
		$output .= sprintf(
			'<a href="%1$s"%2$s class="nav-link %3$s">%4$s</a>',
			esc_url( $item->url ),
			$is_current ? ' aria-current="page"' : '',
			esc_attr( $classes ),
			esc_html( $item->title )
		);

		if ( $has_children ) {
			$output .= sprintf(
				'<button type="button" class="nav-caret" aria-expanded="false" aria-label="%s">'
					. '<svg viewBox="0 0 12 8" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">'
					. '<path stroke-linecap="round" stroke-linejoin="round" d="M1 1l5 6 5-6" /></svg></button>',
				/* translators: %s: menu item name. */
				esc_attr( sprintf( __( 'Show %s categories', 'estore-child' ), $item->title ) )
			);
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</li>';
		}
	}
}
