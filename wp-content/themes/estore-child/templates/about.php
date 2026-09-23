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
		<div class="shell max-w-[760px] space-y-5 leading-relaxed">
			<?php
			while ( have_posts() ) {
				the_post();
				the_content();
			}
			?>
		</div>
	</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/management', null, array( 'post_id' => get_queried_object_id() ) ); ?>

<?php get_footer(); ?>
