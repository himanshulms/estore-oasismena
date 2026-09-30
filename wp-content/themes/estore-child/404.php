<?php
/**
 * 404.
 *
 * Without this the request falls through to BlankSlate's index.php, which
 * prints an unstyled "Not Found" and a bare search box on the dark page.
 *
 * Deliberately not a CFS template: a 404 has to render on an install whose
 * database has no field groups at all, so every string here is translatable
 * rather than editable. The links point at the front page and the products
 * page, both resolved rather than hard-coded.
 */
get_header();

$shop = get_page_by_path( 'products' );
?>

<section class="relative overflow-hidden">
	<div class="glow left-[38%] top-[60px]" aria-hidden="true"></div>

	<div class="relative shell text-center py-20 lg:py-[140px]">
		<p class="eyebrow-text mb-[10px]"><?php esc_html_e( 'Error 404', 'estore-child' ); ?></p>

		<h1 class="display uppercase"><?php esc_html_e( 'Page Not Found', 'estore-child' ); ?></h1>

		<p class="lede mt-5 mx-auto max-w-[520px]">
			<?php esc_html_e( 'The page you are looking for has been moved, renamed, or never existed. Head back to the catalogue to find what you need.', 'estore-child' ); ?>
		</p>

		<div class="flex flex-wrap items-center justify-center gap-4 mt-8">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--ghost">
				<?php esc_html_e( 'Back to Home', 'estore-child' ); ?>
			</a>
			<a href="<?php echo esc_url( $shop ? get_permalink( $shop ) : home_url( '/products/' ) ); ?>" class="btn btn--ghost">
				<?php esc_html_e( 'Browse Products', 'estore-child' ); ?>
				<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
				</svg>
			</a>
		</div>

		<?php
		// The three top categories give a 404 somewhere useful to go.
		$cats = function_exists( 'estore_top_categories' ) ? estore_top_categories() : array();
		?>
		<?php if ( $cats ) : ?>
			<div class="mt-14 pt-10 border-t border-white/10 max-w-[900px] mx-auto">
				<p class="eyebrow-text mb-5"><?php esc_html_e( 'Browse by category', 'estore-child' ); ?></p>
				<div class="flex flex-wrap items-center justify-center gap-3">
					<?php foreach ( $cats as $cat ) : ?>
						<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="notfound-chip">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
