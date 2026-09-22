<?php
/**
 * Template Name: About Page Template
 *
 * Hero + free-form body. Extend with CFS bands as the About design is built out.
 */
get_header();
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<section class="py-20 lg:py-[100px]">
	<div class="shell max-w-3xl">
		<?php
		while ( have_posts() ) {
			the_post();
			echo '<div class="prose-invert text-muted leading-relaxed space-y-5">';
			the_content();
			echo '</div>';
		}
		?>
	</div>
</section>

<?php get_footer(); ?>
