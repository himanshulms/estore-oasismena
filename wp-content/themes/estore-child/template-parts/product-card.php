<?php
/**
 * Product card.
 *
 * One card in the Top Rated row, the Explore Products grid and the Related
 * Products row - all three use this file, so the design only ever lives here.
 * Expects the loop to be positioned on a `products` post.
 *
 * Figma: image panel with a category chip top-right, hairline, then title,
 * brand subtitle and a full-width "Request a Quote" button.
 */
$product_id = get_the_ID();
$subtitle   = function_exists( 'cfs' ) ? cfs()->get( 'product_subtitle', $product_id ) : '';
$terms      = get_the_terms( $product_id, 'product-category' );
$chip       = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
$quote_url  = estore_quote_url( $product_id );
?>
<article class="card overflow-hidden flex flex-col" data-aos="fade-up">

	<a href="<?php the_permalink(); ?>" class="relative block bg-surface-2/40 aspect-[4/3] grid place-items-center p-6">
		<?php if ( $chip ) : ?>
			<span class="chip absolute top-4 right-4 z-10"><?php echo esc_html( $chip ); ?></span>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array(
				'class'   => 'max-h-full w-auto object-contain',
				'loading' => 'lazy',
				'alt'     => esc_attr( get_the_title() ),
			) ); ?>
		<?php else : ?>
			<span class="text-faint text-sm"><?php esc_html_e( 'No image', 'estore-child' ); ?></span>
		<?php endif; ?>
	</a>

	<div class="border-t border-white/[0.06] p-6 flex flex-col gap-1.5 grow">
		<h3 class="text-xl font-semibold tracking-tight">
			<a href="<?php the_permalink(); ?>" class="hover:text-white/80 transition-colors"><?php the_title(); ?></a>
		</h3>

		<?php if ( $subtitle ) : ?>
			<p class="text-sm text-muted"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

		<a href="<?php echo esc_url( $quote_url ); ?>" class="btn btn--ghost w-full mt-5">
			<?php esc_html_e( 'Request a Quote', 'estore-child' ); ?>
		</a>
	</div>
</article>
