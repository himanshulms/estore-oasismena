<?php
/**
 * Feature / service card.
 *
 * Receives $args from get_template_part(): 'index' for the numbered label.
 * Used by templates/home.php inside a `services` loop.
 */
$index = isset( $args['index'] ) ? (int) $args['index'] : 0;
?>
<article class="card p-7 flex flex-col justify-between min-h-[280px]" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $index % 3 ) * 80 ); ?>">
	<div>
		<span class="text-xs font-semibold tracking-widest text-accent">
			<?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
		</span>

		<h3 class="text-xl font-semibold mt-5 mb-3"><?php the_title(); ?></h3>

		<?php if ( has_excerpt() ) : ?>
			<p class="text-muted text-sm leading-relaxed"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ); ?>"
			alt="" class="h-10 w-auto object-contain mt-6 opacity-70" loading="lazy" aria-hidden="true">
	<?php endif; ?>
</article>
