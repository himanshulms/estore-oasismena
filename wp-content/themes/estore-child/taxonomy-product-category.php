<?php
/**
 * Product category archive.
 * Same grid as the E-Store page, scoped to one category.
 */
get_header();
$term  = get_queried_object();
$image = $term ? estore_category_hero( $term->term_id ) : '';
?>

<section class="relative overflow-hidden min-h-[280px] lg:min-h-[340px] flex items-center">
	<?php if ( $image ) : ?>
		<img src="<?php echo esc_url( $image ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover" aria-hidden="true">
		<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/30" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="glow right-[8%] top-[10%]" aria-hidden="true"></div>

	<div class="relative shell py-16">
		<p class="text-xs tracking-[0.18em] uppercase text-muted mb-5"><?php esc_html_e( 'Product Catalogue', 'estore-child' ); ?></p>
		<h1 class="display uppercase"><?php echo esc_html( $term->name ); ?></h1>
		<?php if ( $term->description ) : ?>
			<p class="text-muted mt-5 max-w-xl"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="py-16 lg:py-20">
	<div class="shell">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php estore_render_product_grid( $term->term_id ); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
