<?php
/**
 * Single product.
 *
 * Figma "Product Details" (#215:7380): category hero, gallery with thumbnail
 * rail, detail column (SKU / category / trust bar / share / quote), then
 * description, additional information, key features, specifications and a
 * related products row.
 *
 * CFS fields on the `products` post type: product_subtitle, product_sku,
 * product_gallery (loop: gallery_image), product_description,
 * product_additional, product_features (loop: feature_text),
 * product_specs (loop: spec_label, spec_value).
 */
get_header();

while ( have_posts() ) :
	the_post();

	$id       = get_the_ID();
	$cfs      = function_exists( 'cfs' ) ? cfs() : null;
	$get      = function ( $k ) use ( $cfs, $id ) { return $cfs ? $cfs->get( $k, $id ) : ''; };
	$subtitle = $get( 'product_subtitle' );
	$sku      = $get( 'product_sku' );
	$gallery  = (array) $get( 'product_gallery' );
	$features = (array) $get( 'product_features' );
	$specs    = (array) $get( 'product_specs' );
	$terms    = get_the_terms( $id, 'product-category' );
	$term     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;

	// Main image first, then any gallery rows, de-duplicated.
	$images = array();
	if ( has_post_thumbnail() ) {
		$images[] = get_the_post_thumbnail_url( $id, 'large' );
	}
	foreach ( $gallery as $row ) {
		$src = trim( (string) ( $row['gallery_image'] ?? '' ) );
		if ( $src && ! in_array( $src, $images, true ) ) {
			$images[] = $src;
		}
	}
	?>

	<!-- Category hero -->
	<?php $hero = $term ? get_term_meta( $term->term_id, 'category_image', true ) : ''; ?>
	<section class="relative overflow-hidden min-h-[380px] lg:min-h-[460px] flex items-center">
		<?php if ( $hero ) : ?>
			<img src="<?php echo esc_url( $hero ); ?>" alt="" class="absolute inset-y-0 h-full object-cover object-center w-full lg:w-[69.4%] lg:left-[39.6%]" aria-hidden="true">
			<div class="absolute inset-0 bg-[linear-gradient(to_right,#0A0A0A_0%,#0A0A0A_39.6%,transparent_81.5%)]" aria-hidden="true"></div>
			<!-- Figma 215:7419: same 131px melt into the page as the other heroes -->
			<div class="absolute inset-x-0 bottom-0 h-[131px] bg-gradient-to-b from-transparent to-[#0A0A0A]" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="glow right-[8%] top-[10%]" aria-hidden="true"></div>

		<div class="relative shell py-16">
			<nav class="eyebrow-text mb-[22px]" aria-label="<?php esc_attr_e( 'Breadcrumb', 'estore-child' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors"><?php esc_html_e( 'Home', 'estore-child' ); ?></a>
				<span class="mx-2" aria-hidden="true">&middot;</span>
				<?php if ( $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $term->name ); ?></a>
					<span class="mx-2" aria-hidden="true">&middot;</span>
				<?php endif; ?>
				<span class="text-white"><?php the_title(); ?></span>
			</nav>
			<h1 class="display-hero uppercase"><?php echo esc_html( $term ? $term->name : get_the_title() ); ?></h1>
		</div>
	</section>

	<section class="py-16 lg:py-20">
		<div class="shell grid grid-cols-1 lg:grid-cols-[672fr_508fr] gap-10 lg:gap-[60px]">

			<!-- Gallery: 500x504 panel + a 160px thumbnail rail, 12px apart -->
			<div class="flex flex-col-reverse sm:flex-row gap-3">
				<div class="card grow grid place-items-center lg:h-[504px] aspect-[500/504] sm:aspect-auto p-8 bg-white">
					<?php if ( $images ) : ?>
						<img src="<?php echo esc_url( $images[0] ); ?>" alt="<?php the_title_attribute(); ?>"
							class="max-h-full max-w-full w-auto object-contain" id="gallery-main">
					<?php else : ?>
						<span class="text-[#9AA0AE] text-sm"><?php esc_html_e( 'No image', 'estore-child' ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( count( $images ) > 1 ) : ?>
					<div class="flex sm:flex-col gap-3 sm:w-[160px] shrink-0" role="tablist" aria-label="<?php esc_attr_e( 'Product images', 'estore-child' ); ?>">
						<?php foreach ( $images as $i => $src ) : ?>
							<button type="button" role="tab" aria-selected="<?php echo $i ? 'false' : 'true'; ?>"
								class="gallery-thumb card grid place-items-center w-full aspect-square p-3 bg-white <?php echo $i ? '' : 'border-white/25'; ?>"
								data-full="<?php echo esc_url( $src ); ?>">
								<img src="<?php echo esc_url( $src ); ?>" alt="" class="max-h-full max-w-full object-contain" loading="lazy">
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Detail column -->
			<div>
				<h2 class="display"><?php the_title(); ?></h2>

				<?php if ( $subtitle ) : ?>
					<p class="text-lg font-semibold uppercase mt-3"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>

				<div class="border-t border-white/10 mt-7 pt-7 flex flex-col gap-2.5 text-[15px]">
					<?php if ( $sku ) : ?>
						<p><span class="text-muted"><?php esc_html_e( 'SKU:', 'estore-child' ); ?></span> <span class="font-semibold"><?php echo esc_html( $sku ); ?></span></p>
					<?php endif; ?>
					<?php if ( $term ) : ?>
						<p><span class="text-muted"><?php esc_html_e( 'Category:', 'estore-child' ); ?></span>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="font-semibold hover:text-white/80 transition-colors"><?php echo esc_html( $term->name ); ?></a></p>
					<?php endif; ?>
				</div>

				<?php $trust = (array) $get( 'product_trust' ); ?>
				<?php if ( $trust ) : ?>
					<div class="card mt-7 grid grid-cols-3 divide-x divide-white/10">
						<?php foreach ( $trust as $item ) : ?>
							<div class="px-4 py-5 text-center">
								<p class="font-bold"><?php echo esc_html( $item['trust_value'] ?? '' ); ?></p>
								<p class="text-xs text-muted mt-1"><?php echo esc_html( $item['trust_label'] ?? '' ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/share-links' ); ?>

				<a href="<?php echo esc_url( estore_quote_url( $id ) ); ?>" class="btn btn--ghost w-full mt-8">
					<?php esc_html_e( 'Request a Quote', 'estore-child' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Long-form detail -->
	<section class="pb-16 lg:pb-20">
		<div class="shell flex flex-col gap-12">

			<?php if ( $get( 'product_description' ) || get_the_content() ) : ?>
				<div>
					<h2 class="text-muted text-lg mb-4"><?php esc_html_e( 'Product Description:', 'estore-child' ); ?></h2>
					<div class="space-y-4 leading-relaxed">
						<?php echo $get( 'product_description' ) ? wpautop( wp_kses_post( $get( 'product_description' ) ) ) : apply_filters( 'the_content', get_the_content() ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $get( 'product_additional' ) ) : ?>
				<div class="border-t border-white/10 pt-12">
					<h2 class="text-muted text-lg mb-4"><?php esc_html_e( 'Additional information:', 'estore-child' ); ?></h2>
					<div class="space-y-4 leading-relaxed"><?php echo wpautop( wp_kses_post( $get( 'product_additional' ) ) ); ?></div>
				</div>
			<?php endif; ?>

			<?php if ( $features ) : ?>
				<div class="border-t border-white/10 pt-12">
					<h2 class="text-muted text-lg mb-5"><?php esc_html_e( 'Key Features:', 'estore-child' ); ?></h2>
					<ul class="flex flex-col gap-3.5">
						<?php foreach ( $features as $f ) : ?>
							<li class="flex items-start gap-3.5">
								<svg class="w-4 h-4 mt-1 shrink-0 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h14m0 0l-5-5m5 5l-5 5" />
								</svg>
								<span class="font-medium leading-relaxed"><?php echo esc_html( $f['feature_text'] ?? '' ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $specs ) : ?>
				<div class="border-t border-white/10 pt-12">
					<h2 class="text-muted text-lg mb-5"><?php esc_html_e( 'Specifications:', 'estore-child' ); ?></h2>
					<dl class="max-w-3xl">
						<?php foreach ( $specs as $spec ) : ?>
							<div class="spec-row">
								<dt><?php echo esc_html( $spec['spec_label'] ?? '' ); ?></dt>
								<dd><?php echo esc_html( $spec['spec_value'] ?? '' ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- Related -->
	<?php if ( $term ) : ?>
		<section class="pb-20 lg:pb-[100px]">
			<div class="shell">
				<p class="text-xs tracking-[0.18em] uppercase text-muted mb-5"><?php esc_html_e( 'Product Catalogue', 'estore-child' ); ?></p>
				<h2 class="display uppercase mb-12"><?php esc_html_e( 'Related Products', 'estore-child' ); ?></h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
					<?php estore_render_product_grid( $term->term_id, 3, $id ); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
