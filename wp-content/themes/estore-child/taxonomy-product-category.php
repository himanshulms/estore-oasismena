<?php
/**
 * Product category archive.
 * Same grid as the E-Store page, scoped to one category.
 */
get_header();
$term  = get_queried_object();
$image  = $term ? estore_category_hero( $term->term_id ) : '';
// A brand archive shows its category in the breadcrumb.
$parent = ( $term && $term->parent ) ? get_term( $term->parent, 'product-category' ) : null;
$parent = ( $parent && ! is_wp_error( $parent ) ) ? $parent : null;
?>

<!-- Same hero system as the other pages: per-page framing vars, scrim and
     bottom fade. This template predated that and had its own markup. -->
<section class="relative overflow-hidden min-h-[560px] lg:min-h-[667px] flex items-start"
	style="--hero-left:30.5%;--hero-width:69.5%;--hero-opaque:30.5%;--hero-clear:65.3%;--hero-pt:145px">
	<?php if ( $image ) : ?>
		<img src="<?php echo esc_url( $image ); ?>" alt="" class="hero-media" aria-hidden="true">
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
				<?php if ( $parent ) : ?>
					<a href="<?php echo esc_url( get_term_link( $parent ) ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $parent->name ); ?></a>
					<span aria-hidden="true">/</span>
				<?php endif; ?>
				<span class="text-white"><?php echo esc_html( $term->name ); ?></span>
			</nav>

			<h1 class="display-hero uppercase"><?php echo esc_html( $term->name ); ?></h1>

			<?php if ( $term->description ) : ?>
				<div class="flex items-center gap-6 mt-9 max-w-[424px]">
					<span class="hidden sm:block w-10 h-px bg-accent shrink-0" aria-hidden="true"></span>
					<p class="lede"><?php echo esc_html( $term->description ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="py-16 lg:py-20">
	<div class="shell">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
			<?php estore_render_product_grid( $term->term_id ); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
