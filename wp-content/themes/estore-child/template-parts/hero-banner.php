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
$height  = $is_home ? 'min-h-[560px] lg:min-h-[667px]' : 'min-h-[300px] lg:min-h-[380px]';

if ( ! $slides ) {
	$slides = array( array( 'slide_title' => get_the_title() ) );
}
$multi = count( $slides ) > 1;
?>

<section class="relative overflow-hidden <?php echo esc_attr( $height ); ?>">
	<div class="glow right-[6%] top-[10%]" aria-hidden="true"></div>

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
						<img src="<?php echo esc_url( $image ); ?>" alt=""
							class="absolute inset-0 w-full h-full object-cover" aria-hidden="true">
						<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/20" aria-hidden="true"></div>
					<?php endif; ?>

					<div class="relative shell h-full flex flex-col justify-center py-16 <?php echo $is_home ? 'lg:py-24' : ''; ?>">
						<div class="max-w-[661px]">

							<?php if ( $eyebrow ) : ?>
								<p class="flex items-center gap-2.5 text-xs tracking-[0.18em] uppercase text-muted mb-7" data-aos="fade-up">
									<span class="w-1.5 h-1.5 rounded-full bg-accent" aria-hidden="true"></span>
									<?php echo esc_html( $eyebrow ); ?>
								</p>
							<?php endif; ?>

							<?php if ( $title || $accent ) : ?>
								<h1 class="<?php echo $is_home ? 'display' : 'section-heading'; ?> uppercase" data-aos="fade-up" data-aos-delay="60">
									<?php echo esc_html( $title ); ?>
									<?php if ( $accent ) : ?>
										<span class="outlined block"><?php echo esc_html( $accent ); ?></span>
									<?php endif; ?>
									<?php echo $tail ? esc_html( $tail ) : ''; ?>
								</h1>
							<?php endif; ?>

							<?php if ( $desc ) : ?>
								<div class="flex items-start gap-5 mt-7 max-w-md" data-aos="fade-up" data-aos-delay="120">
									<span class="hidden sm:block w-10 h-px bg-accent mt-3 shrink-0" aria-hidden="true"></span>
									<p class="text-muted leading-relaxed"><?php echo wp_kses_post( $desc ); ?></p>
								</div>
							<?php endif; ?>

							<div class="flex flex-wrap items-center gap-3 mt-9" data-aos="fade-up" data-aos-delay="180">
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
