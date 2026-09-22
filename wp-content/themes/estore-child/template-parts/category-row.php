<?php
/**
 * One zig-zag category band on the home page.
 *
 * $args: 'term' (WP_Term from product-category) and 'index' - even rows put the
 * image left, odd rows put it right, which is what the Figma layout alternates.
 * The brand list under the description is the `products` in that category.
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
<div class="grid grid-cols-1 lg:grid-cols-2">

	<div class="relative min-h-[300px] lg:min-h-[420px] <?php echo $flip ? 'lg:order-2' : ''; ?>">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $term->name ); ?>"
				class="absolute inset-0 w-full h-full object-cover" loading="lazy">
		<?php else : ?>
			<div class="absolute inset-0 bg-surface"></div>
		<?php endif; ?>

		<?php if ( $badge ) : ?>
			<span class="absolute top-6 right-6 px-4 py-1.5 rounded-full border border-white/25 bg-black/40 backdrop-blur text-[11px] tracking-[0.14em] uppercase">
				<?php echo esc_html( $badge ); ?>
			</span>
		<?php endif; ?>
	</div>

	<div class="bg-panel p-8 lg:p-12 flex flex-col justify-center <?php echo $flip ? 'lg:order-1' : ''; ?>">
		<h3 class="text-2xl lg:text-[32px] font-semibold tracking-tight">
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="hover:text-white/80 transition-colors">
				<?php echo esc_html( $term->name ); ?>
			</a>
		</h3>

		<?php if ( $term->description ) : ?>
			<p class="text-sm text-muted mt-3 max-w-lg"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>

		<?php if ( $products->have_posts() ) : ?>
			<ul class="mt-8">
				<?php while ( $products->have_posts() ) : ?>
					<?php
					$products->the_post();
					$sub = function_exists( 'cfs' ) ? cfs()->get( 'product_subtitle', get_the_ID() ) : '';
					?>
					<li class="border-t border-white/[0.06] last:border-b">
						<a href="<?php the_permalink(); ?>" class="flex items-center justify-between gap-4 py-4 group">
							<span class="min-w-0">
								<span class="block font-medium truncate"><?php the_title(); ?></span>
								<?php if ( $sub ) : ?>
									<span class="block text-sm text-muted truncate"><?php echo esc_html( $sub ); ?></span>
								<?php endif; ?>
							</span>
							<svg class="w-4 h-4 shrink-0 text-muted group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
							</svg>
						</a>
					</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
