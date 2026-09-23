<?php
/**
 * Template Name: Home Page Template
 *
 * Section order follows the Figma Home frame: hero -> stats -> marquee ->
 * explore categories -> top rated -> deal band -> who we are -> management.
 * Every band is wrapped in a truthiness check, so an install whose CFS groups
 * are not filled in yet renders a short page rather than a broken one.
 */
get_header();

$get = function ( $key ) {
	return function_exists( 'cfs' ) ? cfs()->get( $key ) : '';
};
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<?php /* --- Stats strip: four figures divided by hairlines --- */ ?>
<?php $stats = (array) $get( 'hero_stats' ); ?>
<?php if ( $stats ) : ?>
<section class="shell mt-14 lg:mt-[100px]">
	<dl class="grid grid-cols-2 lg:grid-cols-4 border-t border-b border-white/10">
		<?php foreach ( $stats as $i => $stat ) : ?>
			<div class="py-6 <?php echo $i ? 'lg:border-l border-white/10 lg:pl-8' : ''; ?>">
				<dd class="text-[36px] font-bold leading-9 tracking-tight"><?php echo esc_html( $stat['stat_value'] ?? '' ); ?></dd>
				<dt class="text-xs text-muted mt-2 uppercase tracking-[0.06em]"><?php echo esc_html( $stat['stat_label'] ?? '' ); ?></dt>
			</div>
		<?php endforeach; ?>
	</dl>
</section>
<?php endif; ?>

<?php /* --- Scrolling keyword marquee --- */ ?>
<?php $ticker = (array) $get( 'marquee_items' ); ?>
<?php if ( $ticker ) : ?>
<section class="marquee" aria-label="<?php esc_attr_e( 'Capabilities', 'estore-child' ); ?>">
	<div class="marquee__track">
		<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
			<?php foreach ( $ticker as $item ) : ?>
				<span class="flex items-center gap-14 text-sm text-muted whitespace-nowrap" <?php echo $pass ? 'aria-hidden="true"' : ''; ?>>
					<?php echo esc_html( $item['marquee_text'] ?? '' ); ?>
					<span class="w-1.5 h-1.5 rounded-full bg-accent" aria-hidden="true"></span>
				</span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Explore categories: alternating image / detail bands --- */ ?>
<?php
$cats = get_terms( array(
	'taxonomy'   => 'product-category',
	'hide_empty' => false,
	'orderby'    => 'term_order',
) );
?>
<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end mb-12">
			<div class="lg:col-span-6">
				<?php if ( $get( 'categories_eyebrow' ) ) : ?>
					<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $get( 'categories_eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2 class="display uppercase max-w-[460px]"><?php echo wp_kses_post( $get( 'categories_heading' ) ?: __( 'Explore Categories', 'estore-child' ) ); ?></h2>
			</div>
			<?php if ( $get( 'categories_description' ) ) : ?>
				<div class="lg:col-span-4 lg:col-start-9">
					<p class="text-sm text-muted leading-relaxed"><?php echo esc_html( $get( 'categories_description' ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="overflow-hidden divide-y divide-white/[0.06] border-y border-white/[0.06]">
			<?php foreach ( $cats as $i => $term ) : ?>
				<?php get_template_part( 'template-parts/category-row', null, array( 'term' => $term, 'index' => $i ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Top rated products --- */ ?>
<?php
$top = new WP_Query( array(
	'post_type'      => 'products',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'meta_key'       => '_estore_top_rated',
	'meta_value'     => '1',
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
) );
// Fall back to the newest products so the row is never empty before an editor
// has flagged anything as top rated.
if ( ! $top->have_posts() ) {
	$top = new WP_Query( array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	) );
}
$shop = get_page_by_path( 'products' );
?>
<?php if ( $top->have_posts() ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell">
		<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
			<div>
				<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $get( 'top_rated_eyebrow' ) ?: __( 'Best Performers', 'estore-child' ) ); ?></p>
				<h2 class="display uppercase"><?php echo esc_html( $get( 'top_rated_heading' ) ?: __( 'Top Rated', 'estore-child' ) ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $shop ? get_permalink( $shop ) : home_url( '/products/' ) ); ?>" class="btn btn--ghost shrink-0">
				<?php esc_html_e( 'Browse All Products', 'estore-child' ); ?>
				<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
				</svg>
			</a>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
			<?php while ( $top->have_posts() ) : ?>
				<?php $top->the_post(); ?>
				<?php get_template_part( 'template-parts/product-card' ); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Deal band --- */ ?>
<?php if ( $get( 'deal_heading' ) ) : ?>
<!-- Figma: the deal band is full-bleed (x=0, w=1440, h=543, r=24) with a
     661px text column sitting on the page's 100px gutter - it is NOT inside
     the 1240px content shell. -->
<section class="py-10 lg:py-14">
	<div class="px-0">
		<div class="relative overflow-hidden rounded-[24px] bg-panel border border-white/[0.06] min-h-[400px] lg:min-h-[543px] flex items-center">

			<?php if ( $get( 'deal_image' ) ) : ?>
				<img src="<?php echo esc_url( $get( 'deal_image' ) ); ?>" alt=""
					class="absolute inset-0 w-full h-full object-cover opacity-25" aria-hidden="true" loading="lazy">
				<div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0A] via-[#0A0A0A]/85 to-[#0A0A0A]/30" aria-hidden="true"></div>
			<?php endif; ?>

			<!-- Figma 215:6704 (navy wash) and 215:6728 (white halo behind the shot) -->
			<div class="glow glow--deal left-[40%] top-[15px]" aria-hidden="true"></div>
			<div class="glow glow--halo left-[69%] top-[85px]" aria-hidden="true"></div>

			<?php if ( $get( 'deal_product_image' ) ) : ?>
				<img src="<?php echo esc_url( $get( 'deal_product_image' ) ); ?>" alt=""
					class="hidden lg:block absolute left-[62%] top-1/2 -translate-y-1/2 w-[357px] max-h-[408px] object-contain"
					aria-hidden="true" loading="lazy">
			<?php endif; ?>

			<div class="relative px-6 lg:pl-[100px] lg:pr-8 py-14 w-full lg:max-w-[761px]" data-aos="fade-up">
				<div class="flex items-center gap-3 mb-7">
					<?php if ( $get( 'deal_badge' ) ) : ?>
						<span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#3A1416] border border-[#E84B50]/40 text-[11px] font-semibold tracking-[0.12em] uppercase text-[#E84B50]">
							<span class="w-1.5 h-1.5 rounded-full bg-[#E84B50]" aria-hidden="true"></span>
							<?php echo esc_html( $get( 'deal_badge' ) ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $get( 'deal_discount' ) ) : ?>
						<span class="px-3.5 py-1.5 rounded-full bg-accent text-sm font-bold"><?php echo esc_html( $get( 'deal_discount' ) ); ?></span>
					<?php endif; ?>
				</div>

				<h2 class="display uppercase lg:max-w-[661px]"><?php echo wp_kses_post( $get( 'deal_heading' ) ); ?></h2>

				<?php if ( $get( 'deal_text' ) ) : ?>
					<p class="lede mt-6 max-w-[520px]"><?php echo wp_kses_post( $get( 'deal_text' ) ); ?></p>
				<?php endif; ?>

				<div class="flex flex-wrap items-center gap-3 mt-8">
					<?php if ( $get( 'deal_button_label' ) ) : ?>
						<a href="<?php echo esc_url( $get( 'deal_button_url' ) ?: '#' ); ?>" class="btn btn--primary">
							<?php echo esc_html( $get( 'deal_button_label' ) ); ?>
							<span class="w-6 h-6 grid place-items-center rounded-full border border-white/30">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
								</svg>
							</span>
						</a>
					<?php endif; ?>
					<?php if ( $get( 'deal_link_label' ) ) : ?>
						<a href="<?php echo esc_url( $get( 'deal_link_url' ) ?: '#' ); ?>" class="btn btn--ghost">
							<?php echo esc_html( $get( 'deal_link_label' ) ); ?>
							<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
							</svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* --- Who we are: vision / mission / values --- */ ?>
<?php $pillars = (array) $get( 'pillars' ); ?>
<?php if ( $get( 'about_heading' ) || $pillars ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
			<div class="lg:col-span-7">
				<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $get( 'about_eyebrow' ) ?: __( 'Who We Are', 'estore-child' ) ); ?></p>
				<h2 class="display uppercase"><?php echo wp_kses_post( $get( 'about_heading' ) ); ?></h2>
			</div>
			<?php if ( $get( 'about_description' ) ) : ?>
				<div class="lg:col-span-4 lg:col-start-9">
					<p class="text-sm text-muted leading-relaxed"><?php echo esc_html( $get( 'about_description' ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $pillars ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-8 mt-10 pt-10 border-t border-white/10">
				<?php foreach ( $pillars as $i => $pillar ) : ?>
					<div data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 80 ); ?>">
						<div class="flex items-center justify-between gap-4">
							<span class="pillar-icon">
								<?php if ( ! empty( $pillar['pillar_icon'] ) ) : ?>
									<img src="<?php echo esc_url( $pillar['pillar_icon'] ); ?>" alt="" class="w-[22px] h-[22px]" aria-hidden="true">
								<?php else : ?>
									<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<?php if ( 0 === $i ) : ?>
											<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" /><circle cx="12" cy="12" r="2.5" />
										<?php elseif ( 1 === $i ) : ?>
											<circle cx="12" cy="12" r="8.5" /><path stroke-linecap="round" d="M12 7.5V12l3 2" />
										<?php else : ?>
											<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5.5c0 4.2-2.9 7.6-7 9-4.1-1.4-7-4.8-7-9V6l7-3z" />
										<?php endif; ?>
									</svg>
								<?php endif; ?>
							</span>
							<span class="ghost-number" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						</div>
						<h3 class="text-xl font-bold leading-8 mt-6 mb-2.5"><?php echo esc_html( $pillar['pillar_title'] ?? '' ); ?></h3>
						<p class="lede"><?php echo esc_html( $pillar['pillar_text'] ?? '' ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php /* --- Management --- */ ?>
<?php $team = (array) $get( 'management' ); ?>
<?php if ( $team ) : ?>
<section class="py-14 lg:py-[60px]">
	<div class="shell text-center">
		<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $get( 'team_eyebrow' ) ?: __( 'Our Team', 'estore-child' ) ); ?></p>
		<h2 class="display uppercase"><?php echo esc_html( $get( 'team_heading' ) ?: __( 'Management', 'estore-child' ) ); ?></h2>

		<div class="relative mt-12">
			<!-- Figma 215:6775: 520px lime circle at 10%, blurred, centred on the grid -->
			<span class="glow glow--team left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>

			<div class="relative grid grid-cols-1 md:grid-cols-2 text-left">
			<?php foreach ( $team as $i => $member ) : ?>
				<div class="flex items-center gap-8 py-10 min-h-[260px] border-white/10 <?php echo $i % 2 === 0 ? 'md:border-r md:pr-10' : 'md:pl-10'; ?> <?php echo $i > 1 ? 'border-t' : ''; ?>"
					data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 2 ) * 80 ); ?>">
					<?php if ( ! empty( $member['member_photo'] ) ) : ?>
						<?php
						// Figma crops each portrait differently inside the circle, so the
						// focus point is per-member rather than always centred.
						$focus = trim( (string) ( $member['member_focus'] ?? '' ) );
						?>
						<div class="team-photo">
							<img src="<?php echo esc_url( $member['member_photo'] ); ?>"
								alt="<?php echo esc_attr( $member['member_name'] ?? '' ); ?>"
								<?php echo $focus ? 'style="object-position:center ' . esc_attr( $focus ) . '"' : ''; ?>
								loading="lazy">
						</div>
					<?php endif; ?>
					<div>
						<h3 class="text-xl font-bold leading-8"><?php echo esc_html( $member['member_name'] ?? '' ); ?></h3>
						<p class="lede mt-1"><?php echo esc_html( $member['member_role'] ?? '' ); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
