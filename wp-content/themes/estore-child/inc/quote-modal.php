<?php
/**
 * Quote Modal.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Request a Quote modal.
 *
 * The button no longer navigates to the contact page; it opens a dialog
 * holding the "Request a Quote" Contact Form 7 form, prefilled with whichever
 * product was clicked. The href stays pointed at the contact page so the
 * control still works with JavaScript off.
 * ---------------------------------------------------------------------- */

/** The CF7 form id backing the quote dialog. */
function estore_quote_form_id() {
	return trim( (string) get_theme_mod( 'quote_form_id' ) );
}

/**
 * Fill the dialog's two selects from the database rather than hardcoding
 * options into the form body, so they follow the catalogue automatically.
 */
add_filter( 'wpcf7_form_tag', 'estore_quote_select_options', 10, 1 );
function estore_quote_select_options( $tag ) {
	if ( empty( $tag['name'] ) || ! in_array( $tag['name'], array( 'quote-category', 'quote-product' ), true ) ) {
		return $tag;
	}

	// First entry is the placeholder. CF7's include_blank would label it
	// "Please choose an option"; the design wants "Select Category".
	$values = array( '' );
	$labels = array( 'quote-category' === $tag['name']
		? __( 'Select Category', 'estore-child' )
		: __( 'Select Product', 'estore-child' ) );

	if ( 'quote-category' === $tag['name'] ) {
		foreach ( estore_top_categories() as $cat ) {
			$values[] = html_entity_decode( $cat->name );
			$labels[] = html_entity_decode( $cat->name );
			foreach ( estore_category_brands( $cat->term_id ) as $brand ) {
				$values[] = html_entity_decode( $brand->name );
				$labels[] = '— ' . html_entity_decode( $brand->name );
			}
		}
	} else {
		foreach ( get_posts( array(
			'post_type'      => 'products',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		) ) as $product ) {
			$values[] = $product->post_title;
			$labels[] = $product->post_title;
		}
	}

	$tag['raw_values'] = $values;
	$tag['values']     = $values;
	$tag['labels']     = $labels;
	return $tag;
}

/** The dialog itself, output once per page. */
add_action( 'wp_footer', 'estore_quote_dialog', 20 );
function estore_quote_dialog() {
	$form_id = estore_quote_form_id();
	if ( ! $form_id || ! shortcode_exists( 'contact-form-7' ) ) {
		return;
	}
	?>
	<div class="quote-modal" id="quote-modal" hidden>
		<div class="quote-modal__backdrop" data-quote-close></div>

		<div class="quote-modal__panel" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
			<button type="button" class="quote-modal__close" data-quote-close
				aria-label="<?php esc_attr_e( 'Close', 'estore-child' ); ?>">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
				</svg>
			</button>

			<h2 class="section-heading" id="quote-modal-title"><?php echo esc_html( estore_quote_label() ); ?></h2>
			<p class="lede mt-2 mb-7">
				<?php esc_html_e( "Please fill out the form below and we'll get back to you with a quotation.", 'estore-child' ); ?>
			</p>

			<?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ); ?>
		</div>
	</div>
	<?php
}
