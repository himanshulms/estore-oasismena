<?php
/**
 * One category band on the home page.
 *
 * Figma geometry: uniform 366px rows sitting flush against each other, each
 * half exactly 620px of the 1240px container, 36px padding in the text half.
 * Even rows put the image left, odd rows put it right.
 *
 * Type: title 30/700, description 12/400, brand 14/600, brand note 11/400,
 * badge 9/400, "View All Products" 12/500.
 */
$term  = $args['term'] ?? null;
$index = (int) ( $args['index'] ?? 0 );
if ( ! $term ) {
	return;
}

$image = get_term_meta( $term->term_id, 'category_image', true );
$badge = get_term_meta( $term->term_id, 'category_badge', true );
$flip  = 1 === $index % 2;

$products = new WP_Query( array(
	'post_type'      => 'products',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	'tax_query'      => array( array(
		'taxonomy' => 'product-category',
		'field'    => 'term_id',
		'terms'    => $term->term_id,
	) ),
) );
?>
<div class="cat-row">

	<div class="cat-row__media <?php echo $flip ? 'lg:order-2' : ''; ?>">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $term->name ); ?>"
				class="absolute inset-0 w-full h-full object-cover" loading="lazy">
		<?php else : ?>
			<div class="absolute inset-0 bg-surface"></div>
		<?php endif; ?>

		<?php if ( $badge ) : ?>
			<span class="absolute top-6 right-6 px-3.5 py-1 rounded-full border border-white/30 bg-black/35 backdrop-blur text-[9px] tracking-[0.12em] uppercase">
				<?php echo esc_html( $badge ); ?>
			</span>
		<?php endif; ?>
	</div>

	<div class="cat-row__body <?php echo $flip ? 'lg:order-1' : ''; ?>">
		<h3 class="section-heading">
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="hover:text-white/80 transition-colors">
				<?php echo esc_html( $term->name ); ?>
			</a>
		</h3>

		<?php if ( $term->description ) : ?>
			<p class="text-xs text-muted leading-[20px] mt-2.5 max-w-[500px]"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>

		<?php if ( $products->have_posts() ) : ?>
			<ul class="mt-6">
				<?php while ( $products->have_posts() ) : ?>
					<?php
					$products->the_post();
					$sub = function_exists( 'cfs' ) ? cfs()->get( 'product_subtitle', get_the_ID() ) : '';
					?>
					<li class="border-t border-white/[0.06] last:border-b">
						<a href="<?php the_permalink(); ?>" class="flex items-center justify-between gap-4 py-3 group">
							<span class="min-w-0">
								<span class="block text-sm font-semibold leading-5 truncate"><?php the_title(); ?></span>
								<?php if ( $sub ) : ?>
									<span class="block text-[11px] leading-4 text-muted truncate mt-0.5"><?php echo esc_html( $sub ); ?></span>
								<?php endif; ?>
							</span>
							<svg class="w-3.5 h-3.5 shrink-0 text-muted group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
							</svg>
						</a>
					</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
		<?php endif; ?>

		<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
			class="inline-flex items-center gap-2 text-xs font-medium mt-5 self-end text-muted hover:text-white transition-colors">
			<?php esc_html_e( 'View All Products', 'estore-child' ); ?>
			<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
			</svg>
		</a>
	</div>
</div>
