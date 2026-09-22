<?php
/**
 * Template Name: Home Page Template
 *
 * Sections below follow the Figma Home frame (#215:6345): hero, logo marquee,
 * then alternating content bands on the 1240px grid with 100px section gaps.
 * Each band reads CFS fields; a band renders nothing when its fields are empty,
 * so this is safe to assign before the field groups are filled in.
 */
get_header();

$cfs = function_exists( 'cfs' ) ? cfs() : null;
$get = function ( $key ) use ( $cfs ) {
	return $cfs ? $cfs->get( $key ) : '';
};
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<?php
/* --- Logo marquee (Figma "Marquee" #215:6431) --- */
$logos = (array) $get( 'partner_logos' );
?>
<?php if ( $logos ) : ?>
<section class="marquee mt-16" aria-label="<?php esc_attr_e( 'Partners', 'estore-child' ); ?>">
	<div class="marquee__track">
		<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
			<?php foreach ( $logos as $logo ) : ?>
				<?php $src = isset( $logo['logo_image'] ) ? trim( (string) $logo['logo_image'] ) : ''; ?>
				<?php if ( ! $src ) { continue; } ?>
				<img src="<?php echo esc_url( $src ); ?>"
					alt="<?php echo esc_attr( $logo['logo_name'] ?? '' ); ?>"
					class="h-7 w-auto object-contain opacity-60 hover:opacity-100 transition-opacity"
					loading="lazy" <?php echo $pass ? 'aria-hidden="true"' : ''; ?>>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Intro band --- */ ?>
<?php if ( $get( 'intro_heading' ) ) : ?>
<section class="py-20 lg:py-[100px]">
	<div class="shell">
		<div class="max-w-3xl" data-aos="fade-up">
			<?php if ( $get( 'intro_eyebrow' ) ) : ?>
				<span class="eyebrow mb-6"><?php echo esc_html( $get( 'intro_eyebrow' ) ); ?></span>
			<?php endif; ?>
			<h2 class="section-heading"><?php echo wp_kses_post( $get( 'intro_heading' ) ); ?></h2>
			<?php if ( $get( 'intro_description' ) ) : ?>
				<p class="lede mt-5"><?php echo wp_kses_post( $get( 'intro_description' ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
/* --- Services grid, from the `services` CPT --- */
$services = new WP_Query( array(
	'post_type'      => 'services',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
) );
?>
<?php if ( $services->have_posts() ) : ?>
<section class="py-20 lg:py-[100px]">
	<div class="shell">
		<?php if ( $get( 'services_heading' ) ) : ?>
			<h2 class="section-heading mb-10" data-aos="fade-up"><?php echo wp_kses_post( $get( 'services_heading' ) ); ?></h2>
		<?php endif; ?>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php $i = 0; ?>
			<?php while ( $services->have_posts() ) : ?>
				<?php $services->the_post(); ?>
				<?php get_template_part( 'template-parts/feature-card', null, array( 'index' => $i++ ) ); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
/* --- Closing CTA band (Figma "Container" #215:6700) --- */
$cta_heading = $get( 'cta_heading' );
?>
<?php if ( $cta_heading ) : ?>
<section class="py-10 lg:py-16">
	<div class="shell">
		<div class="relative overflow-hidden rounded-[24px] bg-surface border border-white/[0.06] min-h-[420px] lg:min-h-[542px] flex items-center">

			<?php if ( $get( 'cta_image' ) ) : ?>
				<img src="<?php echo esc_url( $get( 'cta_image' ) ); ?>" alt=""
					class="absolute inset-0 w-full h-full object-cover opacity-25" aria-hidden="true">
				<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/30" aria-hidden="true"></div>
			<?php endif; ?>

			<div class="glow right-[12%] top-[14%]" aria-hidden="true"></div>

			<div class="relative px-8 lg:px-[100px] py-16 max-w-[661px]" data-aos="fade-up">
				<h2 class="section-heading"><?php echo wp_kses_post( $cta_heading ); ?></h2>
				<?php if ( $get( 'cta_text' ) ) : ?>
					<p class="lede mt-5"><?php echo wp_kses_post( $get( 'cta_text' ) ); ?></p>
				<?php endif; ?>
				<?php if ( $get( 'cta_button_label' ) && $get( 'cta_button_url' ) ) : ?>
					<a href="<?php echo esc_url( $get( 'cta_button_url' ) ); ?>" class="btn btn--primary mt-8">
						<?php echo esc_html( $get( 'cta_button_label' ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
