<?php
/**
 * Template Name: Company Page Template
 *
 * The Company page from the client's layout PDF (OE Website - COMPANY PAGE
 * Layout, 29 Sep 2026): intro, a stats band, Our Foundation, Management,
 * Middle East Footprint, Awards, IMS Policy and Certification.
 *
 * Content comes from the "Company Page" CFS group (field ids 133-159, above
 * the global maximum so nothing collides with Hero Options on the same page -
 * see CLAUDE.md). Every band is wrapped in a truthiness check, so an install
 * whose database lacks the group renders an empty page rather than a broken
 * one, and an editor can retire any band by clearing its fields.
 *
 * The management grid is the shared template part, so this page, Home and
 * About all render one list edited in one place.
 */
get_header();
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<?php
$company = get_queried_object_id();
$g       = function ( $key ) use ( $company ) {
	return function_exists( 'cfs' ) ? trim( (string) cfs()->get( $key, $company ) ) : '';
};
$rows    = function ( $key ) use ( $company ) {
	return function_exists( 'cfs' ) ? (array) cfs()->get( $key, $company ) : array();
};

// The body is stored as plain text; blank lines separate paragraphs.
$paragraphs = array_values( array_filter( array_map( 'trim', preg_split( '/\R{2,}/', $g( 'company_body' ) ) ) ) );
?>

<?php /* --- Intro: eyebrow, heading, wide image, then copy beside a photo --- */ ?>
<?php if ( $g( 'company_heading' ) || $paragraphs ) : ?>
<section class="pt-14 lg:pt-[100px]">
	<div class="shell">
		<div class="text-center max-w-[900px] mx-auto">
			<?php if ( $g( 'company_eyebrow' ) ) : ?>
				<p class="eyebrow-text text-accent-bright mb-[10px]"><?php echo esc_html( $g( 'company_eyebrow' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $g( 'company_heading' ) ) : ?>
				<h2 class="section-heading uppercase"><?php echo esc_html( $g( 'company_heading' ) ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( $g( 'company_image' ) ) : ?>
			<figure class="mt-10 lg:mt-12 rounded-[12px] overflow-hidden" data-aos="fade-up">
				<img src="<?php echo esc_url( estore_image_url( $g( 'company_image' ) ) ); ?>"
					alt="<?php echo esc_attr( $g( 'company_heading' ) ); ?>"
					class="w-full h-auto block" loading="lazy" decoding="async">
			</figure>
		<?php endif; ?>

		<?php if ( $paragraphs ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 mt-10 lg:mt-14 items-end">
				<div class="lg:col-span-7 min-w-0 space-y-5">
					<?php foreach ( $paragraphs as $paragraph ) : ?>
						<p class="lede"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				</div>
				<?php if ( $g( 'company_side_image' ) ) : ?>
					<div class="lg:col-span-5 min-w-0">
						<figure class="rounded-[12px] overflow-hidden" data-aos="fade-up">
							<img src="<?php echo esc_url( estore_image_url( $g( 'company_side_image' ) ) ); ?>"
								alt="" class="w-full h-auto block" loading="lazy" decoding="async">
						</figure>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Stats band --- */ ?>
<?php $stats = $rows( 'company_stats' ); ?>
<?php if ( $stats ) : ?>
<section class="mt-14 lg:mt-[100px]">
	<div class="bg-panel border-y border-white/[0.06] py-12 lg:py-[70px]">
		<div class="shell">
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-10 text-center">
				<?php foreach ( $stats as $i => $stat ) : ?>
					<div data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 80 ); ?>">
						<p class="display leading-none"><?php echo esc_html( $stat['stat_value'] ?? '' ); ?></p>
						<p class="eyebrow-text mt-3"><?php echo esc_html( $stat['stat_label'] ?? '' ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Our Foundation: mission, vision, values --- */ ?>
<?php $foundation = $rows( 'foundation_items' ); ?>
<?php if ( $foundation ) : ?>
<section class="py-14 lg:py-[100px]">
	<div class="shell">
		<h2 class="display uppercase text-center"><?php echo esc_html( $g( 'foundation_heading' ) ?: __( 'Our Foundation', 'estore-child' ) ); ?></h2>

		<div class="relative grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-8 mt-12">
			<div class="glow left-[35%] top-0" aria-hidden="true"></div>
			<?php foreach ( $foundation as $i => $item ) : ?>
				<div class="relative text-center" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 80 ); ?>">
					<span class="pillar-icon mx-auto">
						<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
							<?php if ( 0 === $i ) : ?>
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5.5c0 4.2-2.9 7.6-7 9-4.1-1.4-7-4.8-7-9V6l7-3z" />
							<?php elseif ( 1 === $i ) : ?>
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" /><circle cx="12" cy="12" r="2.5" />
							<?php else : ?>
								<circle cx="12" cy="12" r="8.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12.2l2.4 2.4 4.6-4.9" />
							<?php endif; ?>
						</svg>
					</span>
					<h3 class="text-xl font-bold leading-8 mt-6 mb-2.5"><?php echo esc_html( $item['foundation_title'] ?? '' ); ?></h3>
					<p class="lede"><?php echo nl2br( esc_html( $item['foundation_text'] ?? '' ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Management: pull quote, then the shared grid --- */ ?>
<?php if ( $g( 'management_quote' ) ) : ?>
<section class="pt-4">
	<div class="shell">
		<figure class="max-w-[900px] mx-auto text-center" data-aos="fade-up">
			<blockquote class="lede text-base lg:text-lg leading-relaxed">
				<?php echo esc_html( $g( 'management_quote' ) ); ?>
			</blockquote>
			<?php if ( $g( 'management_quote_by' ) ) : ?>
				<figcaption class="eyebrow-text mt-5 text-white"><?php echo esc_html( $g( 'management_quote_by' ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
	</div>
</section>
<?php endif; ?>

<?php
get_template_part(
	'template-parts/management',
	null,
	array(
		'post_id' => $company,
		'eyebrow' => '',
		'heading' => $g( 'management_heading' ),
	)
);
?>

<?php /* --- Middle East footprint --- */ ?>
<?php if ( $g( 'footprint_image' ) ) : ?>
<section class="py-14 lg:py-[100px]">
	<div class="shell text-center">
		<h2 class="display uppercase"><?php echo esc_html( $g( 'footprint_heading' ) ?: __( 'Middle East Footprint', 'estore-child' ) ); ?></h2>
		<?php
		// Measured off the layout PDF (pdftocairo, since pdftoppm flattens the
		// panel into the page black): a square-cornered #131110 panel 785x574
		// carrying a 609px square map that starts 12px down and overhangs the
		// panel's bottom edge by 47px. The figures below are those as
		// percentages, so the whole thing scales with the column.
		?>
		<div class="relative mt-12 mx-auto max-w-[785px]" data-aos="fade-up">
			<span class="absolute inset-x-0 top-0 h-[92.4%] bg-[#131110]" aria-hidden="true"></span>
			<figure class="relative w-[77.6%] mx-auto pt-[1.53%]">
				<img src="<?php echo esc_url( estore_image_url( $g( 'footprint_image' ) ) ); ?>"
					alt="<?php esc_attr_e( 'Map of the countries Oasis Enterprises serves', 'estore-child' ); ?>"
					class="w-full h-auto block" loading="lazy" decoding="async">
			</figure>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Awards: full-bleed band, same grey as the footprint panel --- */ ?>
<?php if ( $g( 'awards_heading' ) || $g( 'awards_image' ) ) : ?>
<section class="bg-[#131110] py-14 lg:py-[60px]">
	<div class="shell grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">
		<div class="lg:col-span-5 min-w-0">
			<h2 class="display uppercase"><?php echo esc_html( $g( 'awards_heading' ) ); ?></h2>
			<?php if ( $g( 'awards_body' ) ) : ?>
				<p class="lede mt-5"><?php echo esc_html( $g( 'awards_body' ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $g( 'awards_image' ) ) : ?>
			<div class="lg:col-span-7 min-w-0">
				<figure class="rounded-[12px] overflow-hidden" data-aos="fade-up">
					<img src="<?php echo esc_url( estore_image_url( $g( 'awards_image' ) ) ); ?>"
						alt="<?php echo esc_attr( $g( 'awards_caption' ) ); ?>"
						class="w-full h-auto block" loading="lazy" decoding="async">
					<?php if ( $g( 'awards_caption' ) ) : ?>
						<figcaption class="text-xl font-bold leading-8 mt-5"><?php echo esc_html( $g( 'awards_caption' ) ); ?></figcaption>
					<?php endif; ?>
				</figure>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- IMS policy --- */ ?>
<?php if ( $g( 'ims_heading' ) || $g( 'ims_image' ) ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell text-center">
		<h2 class="display uppercase"><?php echo esc_html( $g( 'ims_heading' ) ); ?></h2>
		<?php if ( $g( 'ims_body' ) ) : ?>
			<p class="lede mt-5 max-w-[860px] mx-auto"><?php echo esc_html( $g( 'ims_body' ) ); ?></p>
		<?php endif; ?>
		<?php if ( $g( 'ims_image' ) ) : ?>
			<figure class="company-doc mt-12 mx-auto max-w-[620px]" data-aos="fade-up">
				<img src="<?php echo esc_url( estore_image_url( $g( 'ims_image' ) ) ); ?>"
					alt="<?php echo esc_attr( $g( 'ims_heading' ) ); ?>"
					class="w-full h-auto block" loading="lazy" decoding="async">
			</figure>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Certification --- */ ?>
<?php if ( $g( 'cert_heading' ) || $g( 'cert_image' ) ) : ?>
<section class="py-14 lg:py-[60px] lg:pb-[100px]">
	<div class="shell grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">
		<div class="lg:col-span-5 min-w-0">
			<h2 class="display uppercase"><?php echo esc_html( $g( 'cert_heading' ) ); ?></h2>
			<?php if ( $g( 'cert_body' ) ) : ?>
				<p class="lede mt-5"><?php echo esc_html( $g( 'cert_body' ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $g( 'cert_image' ) ) : ?>
			<div class="lg:col-span-7 min-w-0">
				<figure class="company-doc mx-auto max-w-[520px]" data-aos="fade-up">
					<img src="<?php echo esc_url( estore_image_url( $g( 'cert_image' ) ) ); ?>"
						alt="<?php echo esc_attr( $g( 'cert_heading' ) ); ?>"
						class="w-full h-auto block" loading="lazy" decoding="async">
				</figure>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
