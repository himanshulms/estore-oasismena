<?php
/**
 * Hero banner.
 *
 * CFS loop `hero_slider`, sub-fields: slide_image, slide_eyebrow, slide_title,
 * slide_title_accent (the outlined blue word), slide_title_end,
 * slide_description, slide_cta_label/url, slide_link_label/url.
 *
 * The heading is split into three parts because the Figma hero sets the middle
 * word in an outlined blue treatment ("REDEFINE YOUR *AUDIO* EXPERIENCE").
 * Falls back to the page title so a template still renders with no CFS group.
 */
$slides  = function_exists( 'cfs' ) ? (array) cfs()->get( 'hero_slider' ) : array();
$is_home = is_front_page();

// Each page frames its hero photo differently in the file - see .hero-media.
$is_products = is_page_template( 'templates/products.php' );
$hero_vars   = $is_home
	? '--hero-left:39.6%;--hero-width:69.4%;--hero-opaque:39.6%;--hero-clear:81.5%;--hero-pt:54px'
	: ( $is_products
		? '--hero-left:26.7%;--hero-width:82.4%;--hero-opaque:31.1%;--hero-clear:55.9%;--hero-pt:155px'
		: '--hero-left:30.5%;--hero-width:69.5%;--hero-opaque:30.5%;--hero-clear:65.3%;--hero-pt:145px' );
// Figma: the home hero runs from the 80px header to y=800 before the stats row.
$height  = 'min-h-[560px] lg:min-h-[667px]';

if ( ! $slides ) {
	$slides = array( array( 'slide_title' => get_the_title() ) );
}
$multi = count( $slides ) > 1;
?>

<section class="relative overflow-hidden <?php echo esc_attr( $height ); ?>" style="<?php echo esc_attr( $hero_vars ); ?>">
	<div class="swiper hero-swiper h-full"
		data-slides-per-view="1" data-slides-tablet="1" data-slides-mobile="1"
		data-space-between="0"
		data-loop="<?php echo $multi ? 'true' : 'false'; ?>"
		data-autoplay="<?php echo $multi ? 'true' : 'false'; ?>"
		data-autoplay-delay="6000">

		<div class="swiper-wrapper">
			<?php foreach ( $slides as $slide ) : ?>
				<?php
				$image   = trim( (string) ( $slide['slide_image'] ?? '' ) );
				$eyebrow = trim( (string) ( $slide['slide_eyebrow'] ?? '' ) );
				$title   = trim( (string) ( $slide['slide_title'] ?? '' ) );
				$accent  = trim( (string) ( $slide['slide_title_accent'] ?? '' ) );
				$tail    = trim( (string) ( $slide['slide_title_end'] ?? '' ) );
				$desc    = trim( (string) ( $slide['slide_description'] ?? '' ) );
				?>
				<div class="swiper-slide relative">

					<?php if ( $image ) : ?>
						<img src="<?php echo esc_url( $image ); ?>" alt="" class="hero-media" aria-hidden="true">
						<div class="hero-scrim" aria-hidden="true"></div>
						<div class="hero-fade" aria-hidden="true"></div>
					<?php endif; ?>

					<!-- Figma 215:6383: 500px, #133578 @ 8%, blur(120px). Sits over the
					     photo and under the copy, so it goes after the gradient. -->
					<div class="glow left-[39%] top-[70px]" aria-hidden="true"></div>

					<div class="relative shell hero-copy flex flex-col justify-center lg:justify-start">
						<div class="max-w-[720px]">

							<?php if ( $eyebrow ) : ?>
								<p class="eyebrow-text flex items-center gap-2.5 mb-[22px]" data-aos="fade-up">
									<?php if ( $is_home ) : ?>
										<span class="w-1.5 h-1.5 rounded-full bg-accent" aria-hidden="true"></span>
										<?php echo esc_html( $eyebrow ); ?>
									<?php else : ?>
										<?php
										// Inner pages show it as a breadcrumb: "e-store / Products".
										$crumbs = array_map( 'trim', explode( '/', $eyebrow ) );
										$last   = count( $crumbs ) - 1;
										?>
										<?php foreach ( $crumbs as $i => $crumb ) : ?>
											<span class="<?php echo $i === $last ? 'text-white' : ''; ?>"><?php echo esc_html( $crumb ); ?></span>
											<?php if ( $i !== $last ) : ?><span aria-hidden="true">/</span><?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</p>
							<?php endif; ?>

							<?php if ( $title || $accent ) : ?>
								<h1 class="display-hero uppercase" data-aos="fade-up" data-aos-delay="60">
									<?php echo esc_html( $title ); ?>
									<?php if ( $accent ) : ?>
										<?php get_template_part( 'template-parts/outlined-word', null, array( 'text' => $accent ) ); ?>
									<?php endif; ?>
									<?php echo $tail ? esc_html( $tail ) : ''; ?>
								</h1>
							<?php endif; ?>

							<?php if ( $desc ) : ?>
								<div class="flex items-center gap-6 mt-9 max-w-[424px]" data-aos="fade-up" data-aos-delay="120">
									<span class="hidden sm:block w-10 h-px bg-accent shrink-0" aria-hidden="true"></span>
									<p class="lede"><?php echo wp_kses_post( $desc ); ?></p>
								</div>
							<?php endif; ?>

							<?php // Skip the row entirely when neither button has a label, or its
							      // 60px top margin leaves a gap under the copy. ?>
							<?php if ( ! empty( $slide['slide_cta_label'] ) || ! empty( $slide['slide_link_label'] ) ) : ?>
							<div class="flex flex-wrap items-center gap-6 mt-[60px]" data-aos="fade-up" data-aos-delay="180">
								<?php if ( ! empty( $slide['slide_cta_label'] ) ) : ?>
									<a href="<?php echo esc_url( $slide['slide_cta_url'] ?? '#' ); ?>" class="btn btn--primary">
										<?php echo esc_html( $slide['slide_cta_label'] ); ?>
										<span class="w-6 h-6 grid place-items-center rounded-full border border-white/30">
											<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
												<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
											</svg>
										</span>
									</a>
								<?php endif; ?>

								<?php if ( ! empty( $slide['slide_link_label'] ) ) : ?>
									<a href="<?php echo esc_url( $slide['slide_link_url'] ?? '#' ); ?>" class="btn btn--ghost">
										<?php echo esc_html( $slide['slide_link_label'] ); ?>
										<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
										</svg>
									</a>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $multi ) : ?>
			<div class="swiper-pagination !bottom-8"></div>
		<?php endif; ?>
	</div>
</section>
