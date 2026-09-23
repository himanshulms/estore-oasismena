<?php
/**
 * Management / team grid. Shared by the home page and the About page.
 *
 * People come from the `team` custom post type, so there is one list edited
 * in one place (Team in wp-admin) and both pages stay in step. Title is the
 * name, featured image the portrait, and the CFS group "Team Member" supplies
 * the role and the per-portrait crop focus. Order follows menu_order, so the
 * grid is reordered with Page Attributes.
 *
 * $args['post_id'] is the page supplying the eyebrow and heading only.
 *
 * Figma 215:6775: a 2x2 grid with hairline dividers over a 520px lime wash;
 * portraits masked to a 200px circle on a grey gradient.
 */
$source = (int) ( $args['post_id'] ?? get_queried_object_id() );

$members = get_posts( array(
	'post_type'      => 'team',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
) );
if ( ! $members ) {
	return;
}

$eyebrow = $source && function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'team_eyebrow', $source ) ) : '';
$heading = $source && function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'team_heading', $source ) ) : '';
?>
<section class="py-14 lg:py-[60px]">
	<div class="shell text-center">
		<p class="eyebrow-text mb-[10px]"><?php echo esc_html( $eyebrow ?: __( 'Our Team', 'estore-child' ) ); ?></p>
		<h2 class="display uppercase"><?php echo esc_html( $heading ?: __( 'Management', 'estore-child' ) ); ?></h2>

		<div class="relative mt-12">
			<span class="glow glow--team left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>

			<div class="relative grid grid-cols-1 md:grid-cols-2 text-left">
				<?php foreach ( $members as $i => $member ) : ?>
					<?php
					$role  = function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'member_role', $member->ID ) ) : '';
					$focus = function_exists( 'cfs' ) ? trim( (string) cfs()->get( 'member_focus', $member->ID ) ) : '';
					$photo = get_the_post_thumbnail_url( $member->ID, 'medium_large' );
					?>
					<div class="flex items-center gap-8 py-10 min-h-[260px] border-white/10 <?php echo $i % 2 === 0 ? 'md:border-r md:pr-10' : 'md:pl-10'; ?> <?php echo $i > 1 ? 'border-t' : ''; ?>"
						data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 2 ) * 80 ); ?>">

						<?php if ( $photo ) : ?>
							<div class="team-photo">
								<img src="<?php echo esc_url( $photo ); ?>"
									alt="<?php echo esc_attr( $member->post_title ); ?>"
									<?php echo $focus ? 'style="object-position:center ' . esc_attr( $focus ) . '"' : ''; ?>
									loading="lazy">
							</div>
						<?php endif; ?>

						<div>
							<h3 class="text-xl font-bold leading-8"><?php echo esc_html( $member->post_title ); ?></h3>
							<?php if ( $role ) : ?>
								<p class="lede mt-1"><?php echo esc_html( $role ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
