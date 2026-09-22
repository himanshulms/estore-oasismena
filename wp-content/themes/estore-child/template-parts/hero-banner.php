<?php
/**
 * Hero banner.
 *
 * Included at the top of every page template. Content comes from the CFS
 * loop field `hero_slider` (sub-fields: slide_image, slide_title,
 * slide_description, slide_cta_label, slide_cta_url). Falls back to the page
 * title so a template still renders on an install whose DB has no CFS group.
 *
 * Figma: Home #215:6351 - 667px content area under an 80px header, blurred
 * accent glow behind the art, 24px radii.
 */

$slides = function_exists( 'cfs' ) ? (array) cfs()->get( 'hero_slider' ) : array();
$is_home = is_front_page();
$height  = $is_home ? 'min-h-[560px] lg:min-h-[667px]' : 'min-h-[320px] lg:min-h-[400px]';

if ( ! $slides ) {
	$slides = array( array(
		'slide_title'       => get_the_title(),
		'slide_description' => '',
		'slide_image'       => '',
	) );
}
?>

<section class="relative overflow-hidden <?php echo esc_attr( $height ); ?>">

	<!-- Accent glow (Figma: 384px circle, 100px blur) -->
	<div class="glow right-[8%] top-[12%]" aria-hidden="true"></div>

	<div class="swiper hero-swiper h-full"
		data-slides-per-view="1"
		data-slides-tablet="1"
		data-slides-mobile="1"
		data-space-between="0"
		data-loop="<?php echo count( $slides ) > 1 ? 'true' : 'false'; ?>"
		data-autoplay="<?php echo count( $slides ) > 1 ? 'true' : 'false'; ?>"
		data-autoplay-delay="6000">

		<div class="swiper-wrapper">
			<?php foreach ( $slides as $slide ) : ?>
				<?php
				$image = isset( $slide['slide_image'] ) ? trim( (string) $slide['slide_image'] ) : '';
				$title = isset( $slide['slide_title'] ) ? trim( (string) $slide['slide_title'] ) : '';
				$desc  = isset( $slide['slide_description'] ) ? trim( (string) $slide['slide_description'] ) : '';
				$label = isset( $slide['slide_cta_label'] ) ? trim( (string) $slide['slide_cta_label'] ) : '';
				$url   = isset( $slide['slide_cta_url'] ) ? trim( (string) $slide['slide_cta_url'] ) : '';
				?>
				<div class="swiper-slide relative">

					<?php if ( $image ) : ?>
						<img src="<?php echo esc_url( $image ); ?>" alt=""
							class="absolute inset-0 w-full h-full object-cover opacity-25" aria-hidden="true">
						<!-- Figma: linear-gradient(90deg, #0A0A0A 0%, rgba(10,10,10,.85) 50%, rgba(10,10,10,.3) 100%) -->
						<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/30" aria-hidden="true"></div>
					<?php endif; ?>

					<div class="relative shell h-full flex flex-col justify-center py-20 <?php echo $is_home ? 'lg:py-28' : ''; ?>">
						<div class="max-w-[661px]">
							<?php if ( $title ) : ?>
								<h1 class="<?php echo $is_home ? 'display' : 'section-heading'; ?>" data-aos="fade-up">
									<?php echo wp_kses_post( $title ); ?>
								</h1>
							<?php endif; ?>

							<?php if ( $desc ) : ?>
								<p class="lede mt-5 max-w-xl" data-aos="fade-up" data-aos-delay="80">
									<?php echo wp_kses_post( $desc ); ?>
								</p>
							<?php endif; ?>

							<?php if ( $label && $url ) : ?>
								<div class="mt-8" data-aos="fade-up" data-aos-delay="160">
									<a href="<?php echo esc_url( $url ); ?>" class="btn btn--primary">
										<?php echo esc_html( $label ); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $slides ) > 1 ) : ?>
			<div class="swiper-pagination !bottom-8"></div>
		<?php endif; ?>
	</div>
</section>
