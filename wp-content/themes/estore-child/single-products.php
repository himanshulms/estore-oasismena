<?php
/**
 * Single product - Figma "Product Details" 215:7163.
 *
 * Layout on the 1240 container: a 650px gallery, a 36px gutter, then a 554px
 * detail column. The gallery is a 650x530 white panel with a row of three
 * 201x160 white thumbnails 24px beneath it. The detail column runs title (60/700),
 * subtitle (20), a "Description:" label (20, muted) with its body, a hairline,
 * then SKU and Category, and closes with a full-width quote button.
 *
 * Everything here is editable in wp-admin: the CFS group "Product Details" on
 * the `products` post type supplies the copy, the category comes from the
 * `product-category` taxonomy, and the repeated labels are Customizer settings.
 */
get_header();

while ( have_posts() ) :
	the_post();

	$id       = get_the_ID();
	$cfs      = function_exists( 'cfs' ) ? cfs() : null;
	$get      = function ( $k ) use ( $cfs, $id ) { return $cfs ? $cfs->get( $k, $id ) : ''; };
	$subtitle = $get( 'product_subtitle' );
	$sku      = $get( 'product_sku' );
	$desc     = $get( 'product_description' );
	$gallery  = (array) $get( 'product_gallery' );
	// A product is filed under a BRAND, which is a child of a category.
	// The hero names the category (Figma shows "PROFESSIONAL LIGHTING"), while
	// the "Category:" line names the brand ("Category: RBS Sound").
	$term  = estore_product_category( $id );
	$brand = estore_product_brand( $id );

	// Featured image first, then any gallery rows, de-duplicated.
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
	// Figma shows a 3-up row (3x201 + 2x24 = 651 in the 650 column). Products
// carry up to six images, so keep that sizing and let extras wrap.
$thumbs = $images;
	$hero   = $term ? estore_category_hero( $term->term_id ) : '';
	?>

	<!-- Category hero -->
	<section class="relative overflow-hidden min-h-[560px] lg:min-h-[667px] flex items-start"
		style="--hero-left:30.5%;--hero-width:69.5%;--hero-opaque:30.5%;--hero-clear:65.3%;--hero-pt:145px">
		<?php if ( $hero ) : ?>
			<img src="<?php echo esc_url( estore_image_url( $hero ) ); ?>" alt="" class="hero-media" aria-hidden="true">
			<div class="hero-scrim" aria-hidden="true"></div>
			<div class="hero-fade" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="glow left-[39%] top-[70px]" aria-hidden="true"></div>

		<div class="relative shell hero-copy w-full">
			<div class="max-w-[673px]">
			<nav class="eyebrow-text mb-[22px] flex items-center gap-2" aria-label="<?php esc_attr_e( 'Breadcrumb', 'estore-child' ); ?>">
				<?php $shop = get_page_by_path( 'products' ); ?>
				<a href="<?php echo esc_url( $shop ? get_permalink( $shop ) : home_url( '/products/' ) ); ?>" class="hover:text-white transition-colors">
					<?php esc_html_e( 'Products', 'estore-child' ); ?>
				</a>
				<span aria-hidden="true">/</span>
				<span class="text-white"><?php esc_html_e( 'Product details', 'estore-child' ); ?></span>
			</nav>
			<h1 class="display-hero uppercase"><?php echo esc_html( $term ? $term->name : get_the_title() ); ?></h1>

			<?php
			// Figma 215:7428. The hero copy is sourced from the Products page
			// hero so an editor changes it in one place. The CTA pair the file
			// also shows here is deliberately omitted - see CLAUDE.md.
			$shop_page = get_page_by_path( 'products' );
			$shop_hero = ( $shop_page && function_exists( 'cfs' ) ) ? (array) cfs()->get( 'hero_slider', $shop_page->ID ) : array();
			$shop_hero = $shop_hero[0] ?? array();
			?>

			<?php if ( ! empty( $shop_hero['slide_description'] ) ) : ?>
				<div class="flex items-center gap-6 mt-9 max-w-[424px]">
					<span class="hidden sm:block w-10 h-px bg-accent shrink-0" aria-hidden="true"></span>
					<p class="lede"><?php echo wp_kses_post( $shop_hero['slide_description'] ); ?></p>
				</div>
			<?php endif; ?>

		</div>
	</section>

	<!-- Gallery + detail: 650 / 36 / 554 -->
	<section class="pt-12 lg:pt-[87px]">
		<div class="shell grid grid-cols-1 lg:grid-cols-[650fr_554fr] gap-8 lg:gap-9">

			<div>
				<div class="bg-white rounded-[10px] relative h-[320px] sm:h-[420px] lg:h-[530px]">
					<?php if ( $images ) : ?>
						<img src="<?php echo esc_url( estore_image_url( $images[0] ) ); ?>" alt="<?php the_title_attribute(); ?>"
							class="absolute inset-0 w-full h-full object-contain p-12" id="gallery-main">
					<?php else : ?>
						<span class="absolute inset-0 grid place-items-center text-[#9AA0AE] text-sm"><?php esc_html_e( 'No image', 'estore-child' ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( count( $thumbs ) > 1 ) : ?>
					<div class="grid grid-cols-3 gap-6 mt-6" role="tablist" aria-label="<?php esc_attr_e( 'Product images', 'estore-child' ); ?>">
						<?php foreach ( $thumbs as $i => $src ) : ?>
							<button type="button" role="tab" aria-selected="<?php echo $i ? 'false' : 'true'; ?>"
								class="gallery-thumb relative bg-white rounded-[10px] h-[110px] lg:h-[160px] overflow-hidden"
								data-full="<?php echo esc_url( estore_image_url( $src ) ); ?>">
								<img src="<?php echo esc_url( estore_image_url( $src ) ); ?>" alt="" class="absolute inset-0 w-full h-full object-contain p-6" loading="lazy">
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="flex flex-col">
				<h2 class="display leading-[76px]"><?php the_title(); ?></h2>

				<?php if ( $subtitle ) : ?>
					<p class="text-xl font-bold leading-[25px] mt-[11px] uppercase"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>

				<?php if ( $desc ) : ?>
					<p class="text-xl leading-[25px] text-muted mt-[24px]"><?php echo esc_html( estore_label( 'description_label', __( 'Description:', 'estore-child' ) ) ); ?></p>
					<div class="text-sm leading-[23px] mt-3 space-y-4"><?php echo wpautop( wp_kses_post( $desc ) ); ?></div>
				<?php endif; ?>

				<div class="border-t border-white/10 mt-8 pt-6 flex flex-col gap-3 text-xl leading-[25px]">
					<?php if ( $sku ) : ?>
						<p><span class="text-muted"><?php esc_html_e( 'SKU:', 'estore-child' ); ?></span> <?php echo esc_html( $sku ); ?></p>
					<?php endif; ?>
					<?php $shown = $brand ?: $term; ?>
					<?php if ( $shown ) : ?>
						<p><span class="text-muted"><?php esc_html_e( 'Category:', 'estore-child' ); ?></span>
							<a href="<?php echo esc_url( get_term_link( $shown ) ); ?>" class="hover:text-white/80 transition-colors"><?php echo esc_html( $shown->name ); ?></a></p>
					<?php endif; ?>
				</div>

				<a href="<?php echo esc_url( estore_quote_url( $id ) ); ?>" class="btn-quote mt-auto lg:mt-12"
					data-quote-product="<?php the_title_attribute(); ?>"
					data-quote-category="<?php echo esc_attr( $brand ? $brand->name : ( $term ? $term->name : '' ) ); ?>">
					<?php echo esc_html( estore_quote_label() ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Related products -->
	<?php if ( $term ) : ?>
		<section class="pt-24 lg:pt-[100px] pb-20 lg:pb-[100px]">
			<div class="shell">
				<p class="eyebrow-text mb-[10px]"><?php echo esc_html( estore_label( 'related_eyebrow', __( 'Product Catalogue', 'estore-child' ) ) ); ?></p>
				<h2 class="display uppercase max-w-[420px] mb-10"><?php echo esc_html( estore_label( 'related_heading', __( 'Related Products', 'estore-child' ) ) ); ?></h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
					<?php estore_render_product_grid( $term->term_id, 3, $id ); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
