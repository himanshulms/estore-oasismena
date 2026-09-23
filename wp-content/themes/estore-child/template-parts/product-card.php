<?php
/**
 * Product card - Explore Products grid, Related Products and Top Rated.
 *
 * Figma 222:7699: 397x409, radius 10, background #FFFFFF @ 5%. A 395x249
 * pure-white image panel carries the category chip (white @ 60%, #133578
 * label, 9/600), then 24px padding around a 20/700 title, a 12/400 subtitle
 * and a 349x38 quote button (radius 12, white @ 5%).
 */
$product_id = get_the_ID();
$subtitle   = function_exists( 'cfs' ) ? cfs()->get( 'product_subtitle', $product_id ) : '';
$terms      = get_the_terms( $product_id, 'product-category' );
$chip       = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
?>
<article class="product-card" data-aos="fade-up">

	<a href="<?php the_permalink(); ?>" class="product-card__media">
		<?php if ( $chip ) : ?>
			<span class="chip absolute top-[25px] right-[25px] z-10"><?php echo esc_html( $chip ); ?></span>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array(
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title() ),
			) ); ?>
		<?php else : ?>
			<span class="absolute inset-0 grid place-items-center text-[#9AA0AE] text-sm"><?php esc_html_e( 'No image', 'estore-child' ); ?></span>
		<?php endif; ?>
	</a>

	<div class="product-card__body">
		<h3 class="text-xl font-bold leading-7">
			<a href="<?php the_permalink(); ?>" class="hover:text-white/80 transition-colors"><?php the_title(); ?></a>
		</h3>

		<?php if ( $subtitle ) : ?>
			<p class="text-xs text-muted leading-4 mt-[5px]"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

		<a href="<?php echo esc_url( estore_quote_url( $product_id ) ); ?>" class="btn-quote product-card__cta">
			<?php echo esc_html( estore_quote_label() ); ?>
		</a>
	</div>
</article>
