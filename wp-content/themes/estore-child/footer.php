</main>

<?php
$socials = array(
	'linkedin_link'  => __( 'LinkedIn', 'estore-child' ),
	'instagram_link' => __( 'Instagram', 'estore-child' ),
	'facebook_link'  => __( 'Facebook', 'estore-child' ),
	'x_link'         => __( 'X', 'estore-child' ),
	'whatsapp_link'  => __( 'WhatsApp', 'estore-child' ),
);
$footer_logo = get_theme_mod( 'footer_logo' );
$footer_desc = get_theme_mod( 'footer_description' );
$email       = get_theme_mod( 'email_link' );
$phone       = get_theme_mod( 'phone_link' );
$address     = get_theme_mod( 'address_link' );
?>

<footer class="site-footer mt-24 pt-16 pb-8">
	<div class="shell">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

			<div class="lg:col-span-2 max-w-sm">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center mb-5">
					<?php if ( $footer_logo ) : ?>
						<img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto">
					<?php else : ?>
						<span class="text-lg font-semibold tracking-tight"><?php bloginfo( 'name' ); ?></span>
					<?php endif; ?>
				</a>

				<?php if ( $footer_desc ) : ?>
					<p class="text-muted text-sm leading-relaxed"><?php echo esc_html( $footer_desc ); ?></p>
				<?php endif; ?>

				<div class="flex items-center gap-3 mt-6">
					<?php foreach ( $socials as $key => $label ) : ?>
						<?php $url = get_theme_mod( $key ); ?>
						<?php if ( $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"
								class="w-9 h-9 grid place-items-center rounded-full border border-white/10 text-muted hover:text-white hover:border-white/25 transition-colors"
								aria-label="<?php echo esc_attr( $label ); ?>">
								<span class="text-xs"><?php echo esc_html( mb_substr( $label, 0, 1 ) ); ?></span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<div>
				<h2 class="text-sm font-semibold mb-4"><?php esc_html_e( 'Explore', 'estore-child' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer_menu',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-3 text-sm text-muted',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

			<div>
				<h2 class="text-sm font-semibold mb-4"><?php esc_html_e( 'Contact', 'estore-child' ); ?></h2>
				<ul class="flex flex-col gap-3 text-sm text-muted">
					<?php if ( $email ) : ?>
						<li><a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a></li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $address ) : ?>
						<li class="leading-relaxed"><?php echo nl2br( esc_html( $address ) ); ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>

		<div class="mt-12 pt-6 border-t border-white/[0.06] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-faint">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'estore-child' ); ?></p>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'quick_links',
				'container'      => false,
				'menu_class'     => 'flex items-center gap-5',
				'fallback_cb'    => false,
				'depth'          => 1,
			) );
			?>
		</div>
	</div>
</footer>

<button type="button" class="backtotop" id="backtotop" aria-label="<?php esc_attr_e( 'Back to top', 'estore-child' ); ?>">
	<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
		<path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7" />
	</svg>
</button>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/js/script.js' ); ?>?v=<?php echo time(); ?>"></script>

<?php wp_footer(); ?>
</body>
</html>
