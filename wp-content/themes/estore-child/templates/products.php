<?php
/**
 * Template Name: Products (E-Store) Template
 *
 * Hero, then "EXPLORE PRODUCTS" with an All Categories dropdown that swaps the
 * grid over AJAX (assets/js/products.js -> estore_filter_products()).
 * The <select> is a real form that submits on its own when JS is off, so the
 * filter degrades rather than breaking.
 */
get_header();

$cats    = get_terms( array( 'taxonomy' => 'product-category', 'hide_empty' => false ) );
$current = isset( $_GET['category'] ) ? absint( $_GET['category'] ) : 0;
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<section class="pt-14 lg:pt-[100px] pb-14 lg:pb-[60px]">
	<div class="shell">

		<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-9">
			<div class="lg:w-[337px] shrink-0">
				<?php
				// Page-level CFS wins, then the Customizer default, then the string.
				$eyebrow = function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'products_eyebrow' ) ) : '';
				$heading = function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'products_heading' ) ) : '';
				?>
				<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $eyebrow ?: estore_label( 'catalogue_eyebrow', __( 'Product Catalogue', 'estore-child' ) ) ); ?></p>
				<h2 class="display uppercase"><?php echo esc_html( $heading ?: estore_label( 'catalogue_heading', __( 'Explore Products', 'estore-child' ) ) ); ?></h2>
			</div>

			<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
				<form method="get" action="<?php echo esc_url( get_permalink() ); ?>" class="shrink-0" id="product-filter"
					data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'estore_products' ) ); ?>">
					<label for="category" class="sr-only"><?php esc_html_e( 'Filter by category', 'estore-child' ); ?></label>
					<select name="category" id="category" class="cat-select">
						<option value="0"><?php echo esc_html( estore_label( 'all_categories', __( 'All Categories', 'estore-child' ) ) ); ?></option>
						<?php foreach ( $cats as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $current, $cat->term_id ); ?>>
								<?php echo esc_html( $cat->name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<noscript><button type="submit" class="btn btn--ghost ml-2"><?php esc_html_e( 'Filter', 'estore-child' ); ?></button></noscript>
				</form>
			<?php endif; ?>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch" id="products-grid" style="scroll-margin-top:110px" aria-live="polite" aria-busy="false">
			<?php estore_render_product_grid( $current ); ?>
		</div>
	</div>
</section>

<script src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/js/products.js' ); ?>?v=<?php echo time(); ?>"></script>
<?php get_footer(); ?>
