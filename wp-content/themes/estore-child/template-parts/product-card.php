<?php
/**
 * Product card - Top Rated row, Explore Products grid and Related Products.
 *
 * Figma: 398px wide, ~435px tall, 24px padding, 12px radius. The image panel
 * is a fixed height with the shot contained inside it, so cards in a row stay
 * the same height whatever the image aspect ratio. Chip 9/600, title 20/700,
 * subtitle 12/400, button label 12/600.
 */
$product_id = get_the_ID();
$subtitle   = function_exists( 'cfs' ) ? cfs()->get( 'product_subtitle', $product_id ) : '';
$terms      = get_the_terms( $product_id, 'product-category' );
$chip       = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
?>
<article class="card overflow-hidden flex flex-col h-full" data-aos="fade-up">

	<!-- The image panel is pure white in the design; product shots are
	     transparent PNGs, so they sit on it directly. Absolute positioning
	     keeps every card the same height whatever the image aspect. -->
	<a href="<?php the_permalink(); ?>" class="relative block h-[250px] shrink-0 bg-white">
		<?php if ( $chip ) : ?>
			<span class="absolute top-4 right-4 z-10 px-2.5 py-1 rounded-full bg-[#F0F2F7] text-[#4E679A] text-[9px] font-semibold leading-[14px] whitespace-nowrap">
				<?php echo esc_html( $chip ); ?>
			</span>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array(
				'class'   => 'absolute inset-0 w-full h-full object-contain p-7',
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title() ),
			) ); ?>
		<?php else : ?>
			<span class="absolute inset-0 grid place-items-center text-[#9AA0AE] text-sm"><?php esc_html_e( 'No image', 'estore-child' ); ?></span>
		<?php endif; ?>
	</a>

	<div class="border-t border-white/[0.06] p-6 flex flex-col grow">
		<h3 class="text-xl font-bold leading-7 tracking-tight">
			<a href="<?php the_permalink(); ?>" class="hover:text-white/80 transition-colors"><?php the_title(); ?></a>
		</h3>

		<?php if ( $subtitle ) : ?>
			<p class="text-xs text-muted leading-4 mt-1.5"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

		<a href="<?php echo esc_url( estore_quote_url( $product_id ) ); ?>"
			class="btn btn--ghost w-full mt-auto pt-3.5 pb-3.5 !text-xs !font-semibold">
			<?php esc_html_e( 'Request a Quote', 'estore-child' ); ?>
		</a>
	</div>
</article>
