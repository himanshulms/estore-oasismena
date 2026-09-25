<?php
/**
 * Template Name: About Page Template
 *
 * There is no About frame in the Figma file, so this page is assembled from
 * parts that are: the shared hero (Hero Options, as on every page) and the
 * Management grid, which uses the same template part as the home page.
 *
 * Content is CFS-driven throughout - the hero from "Hero Options" and the
 * team from "About Page Sections", both editable on the page in wp-admin.
 * Editor body copy, if any, renders between the two.
 */
get_header();
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<?php
// Free-form body copy from the editor, shown only when the page has content.
$body = get_the_content();
?>
<?php if ( trim( (string) $body ) !== '' ) : ?>
	<section class="pt-14 lg:pt-[100px]">
		<div class="shell">
			<div class="max-w-[760px] space-y-5 leading-relaxed">
				<?php
				while ( have_posts() ) {
					the_post();
					the_content();
				}
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
$about = get_queried_object_id();
$g     = function ( $key ) use ( $about ) {
	return function_exists( 'cfs' ) ? trim( (string) cfs()->get( $key, $about ) ) : '';
};
?>

<?php /* --- Range: heading left, copy right, same split as Explore Categories --- */ ?>
<?php if ( $g( 'range_heading' ) || $g( 'range_body' ) ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16">
		<div class="lg:col-span-5">
			<h2 class="section-heading"><?php echo esc_html( $g( 'range_heading' ) ); ?></h2>
		</div>
		<?php if ( $g( 'range_body' ) ) : ?>
			<div class="lg:col-span-7">
				<p class="lede"><?php echo esc_html( $g( 'range_body' ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Why choose us: intro left, numbered points right --- */ ?>
<?php $why = function_exists( 'cfs' ) ? (array) cfs()->get( 'why_items', $about ) : array(); ?>
<?php if ( $g( 'why_heading' ) || $why ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16">
		<div class="lg:col-span-5">
			<?php if ( $g( 'why_heading' ) ) : ?>
				<h2 class="section-heading"><?php echo esc_html( $g( 'why_heading' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $g( 'why_body' ) ) : ?>
				<p class="lede mt-4"><?php echo esc_html( $g( 'why_body' ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $why ) : ?>
			<div class="lg:col-span-7 flex flex-col">
				<?php foreach ( $why as $i => $item ) : ?>
					<div class="py-7 <?php echo $i ? 'border-t border-white/[0.06]' : 'lg:pt-0'; ?>"
						data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 60 ); ?>">
						<h3 class="text-xl font-bold leading-8"><?php echo esc_html( $item['item_title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $item['item_text'] ) ) : ?>
							<p class="lede mt-2"><?php echo esc_html( $item['item_text'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/management', null, array( 'post_id' => get_queried_object_id() ) ); ?>

<?php /* --- Founder band: full-bleed, same treatment as the home deal band --- */ ?>
<?php if ( $g( 'founder_title' ) || $g( 'founder_body' ) ) : ?>
<section class="py-10 lg:py-14">
	<div class="relative overflow-hidden rounded-[24px] bg-panel border border-white/[0.06] min-h-[320px] lg:min-h-[400px] flex items-center">
		<?php if ( $g( 'founder_image' ) ) : ?>
			<img src="<?php echo esc_url( estore_image_url( $g( 'founder_image' ) ) ); ?>" alt=""
				class="absolute inset-0 w-full h-full object-cover opacity-25" aria-hidden="true" loading="lazy">
			<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/30" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="glow left-[40%] top-[15px]" aria-hidden="true"></div>

		<div class="relative px-6 lg:px-[100px] py-14 w-full" data-aos="fade-up">
			<h2 class="display uppercase"><?php echo esc_html( $g( 'founder_title' ) ); ?></h2>

			<?php if ( $g( 'founder_subtitle' ) ) : ?>
				<p class="text-xl font-bold leading-8 mt-4"><?php echo esc_html( $g( 'founder_subtitle' ) ); ?></p>
			<?php endif; ?>

			<?php if ( $g( 'founder_body' ) ) : ?>
				<p class="lede mt-5 max-w-[1100px]"><?php echo esc_html( $g( 'founder_body' ) ); ?></p>
			<?php endif; ?>

			<?php if ( $g( 'founder_button_label' ) ) : ?>
				<a href="<?php echo esc_url( $g( 'founder_button_url' ) ?: '#' ); ?>" class="btn btn--primary mt-9">
					<?php echo esc_html( $g( 'founder_button_label' ) ); ?>
					<span class="w-6 h-6 grid place-items-center rounded-full border border-white/30">
						<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
						</svg>
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>


<?php get_footer(); ?>
