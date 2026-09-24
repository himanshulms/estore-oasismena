</main>

<?php
$socials = array(
	'facebook_link'  => array( __( 'Facebook', 'estore-child' ),  'M15 8h-3v8h-3V8H7V5.5h2V4.2C9 2.4 10 1.5 12 1.5h3V4h-2c-.6 0-1 .4-1 1v.5h3L15 8z' ),
	'instagram_link' => array( __( 'Instagram', 'estore-child' ), 'M12 7.4a4.6 4.6 0 100 9.2 4.6 4.6 0 000-9.2zm0 7.6a3 3 0 110-6 3 3 0 010 6zM17 6a1 1 0 100 2 1 1 0 000-2zM3 8a5 5 0 015-5h8a5 5 0 015 5v8a5 5 0 01-5 5H8a5 5 0 01-5-5V8zm5-3a3 3 0 00-3 3v8a3 3 0 003 3h8a3 3 0 003-3V8a3 3 0 00-3-3H8z' ),
	'x_link'         => array( __( 'X', 'estore-child' ),         'M17.5 3h3l-6.6 7.5L21.8 21h-6l-4.7-6.1L5.7 21H2.6l7-8L2.2 3h6.2l4.2 5.6L17.5 3zm-1 16h1.7L7.6 4.8H5.8L16.5 19z' ),
	'linkedin_link'  => array( __( 'LinkedIn', 'estore-child' ),  'M6.9 8.8v10.4H3.5V8.8h3.4zM5.2 3.4a2 2 0 110 4 2 2 0 010-4zM20.5 19.2h-3.4v-5.4c0-1.4-.5-2.3-1.7-2.3-.9 0-1.5.6-1.7 1.3-.1.2-.1.6-.1.9v5.5H10s.1-9.4 0-10.4h3.4v1.5c.5-.7 1.3-1.7 3.1-1.7 2.3 0 4 1.5 4 4.7v5.9z' ),
);
$footer_logo = get_theme_mod( 'footer_logo' );
$footer_desc = get_theme_mod( 'footer_description' );
$email       = get_theme_mod( 'email_link' );
$phone       = get_theme_mod( 'phone_link' );
$wordmark    = get_theme_mod( 'footer_wordmark', 'OASIS' );
$contact     = get_page_by_path( 'contact' );
?>

<footer class="site-footer mt-24">
	<div class="shell pt-16 pb-8">

		<!-- Oversized wordmark with the "Let's Work Together" CTA floated over it -->
		<div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
			<?php if ( $wordmark ) : ?>
				<span class="footer-wordmark" aria-hidden="true"><?php echo esc_html( $wordmark ); ?></span>
			<?php endif; ?>

			<div class="flex items-center gap-6 shrink-0">
				<div class="text-right">
					<p class="text-sm font-semibold leading-5"><?php esc_html_e( "Let's Work Together", 'estore-child' ); ?></p>
					<p class="text-xs text-muted mt-1"><?php esc_html_e( 'Get in touch with our team', 'estore-child' ); ?></p>
				</div>
				<a href="<?php echo esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'Contact us', 'estore-child' ); ?>
					<span class="w-6 h-6 grid place-items-center rounded-full border border-white/30">
						<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6m0 0H9m9 0v9" />
						</svg>
					</span>
				</a>
			</div>
		</div>

		<div class="mt-12 pt-12 border-t border-white/10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

			<div class="max-w-xs">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center mb-5">
					<?php if ( $footer_logo ) : ?>
						<img src="<?php echo esc_url( estore_image_url( $footer_logo ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-9 w-auto">
					<?php else : ?>
						<span class="text-lg font-semibold tracking-tight"><?php bloginfo( 'name' ); ?></span>
					<?php endif; ?>
				</a>

				<?php if ( $footer_desc ) : ?>
					<p class="text-muted text-sm font-medium leading-[23px]"><?php echo esc_html( $footer_desc ); ?></p>
				<?php endif; ?>

				<ul class="flex flex-col gap-3 mt-7 text-sm font-medium text-muted">
					<?php if ( $phone ) : ?>
						<li class="flex items-center gap-3">
							<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.28 6.72 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.37a1.12 1.12 0 00-.85-1.09l-4.42-1.1a1.12 1.12 0 00-1.17.42l-.97 1.29a12.04 12.04 0 01-5.5-5.5l1.29-.97c.36-.27.53-.74.42-1.17l-1.1-4.42a1.12 1.12 0 00-1.09-.85H4.5A2.25 2.25 0 002.25 6.75z" />
							</svg>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<li class="flex items-center gap-3">
							<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6-9.75-6" />
							</svg>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

			<div>
				<h2 class="text-xs font-semibold mb-5"><?php esc_html_e( 'Get Involved', 'estore-child' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer_menu',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-3 text-sm text-muted [&_a:hover]:text-white [&_a]:transition-colors',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

			<div>
				<h2 class="text-xs font-semibold mb-5"><?php esc_html_e( 'Privacy Policy', 'estore-child' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'legal_menu',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-3 text-sm text-muted [&_a:hover]:text-white [&_a]:transition-colors',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

			<div>
				<h2 class="text-xs font-semibold mb-5"><?php esc_html_e( 'Quick Links', 'estore-child' ); ?></h2>
				<?php
				// The design lists the product categories here.
				$cats = array_slice( estore_top_categories(), 0, 5 );
				?>
				<?php if ( $cats ) : ?>
					<ul class="flex flex-col gap-3 text-sm text-muted">
						<?php foreach ( $cats as $cat ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $cat->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<h2 class="text-xs font-semibold mt-8 mb-4"><?php esc_html_e( 'Newsletter Subscribe', 'estore-child' ); ?></h2>
				<?php if ( shortcode_exists( 'contact-form-7' ) && get_theme_mod( 'newsletter_form_id' ) ) : ?>
					<?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( get_theme_mod( 'newsletter_form_id' ) ) . '"]' ); ?>
				<?php else : ?>
					<form class="flex" action="<?php echo esc_url( $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ); ?>" method="get">
						<label for="newsletter-email" class="sr-only"><?php esc_html_e( 'Email address', 'estore-child' ); ?></label>
						<input type="email" name="email" id="newsletter-email" class="field" placeholder="email@example.com" required>
						<button type="submit" class="bg-accent hover:bg-[#1b4699] transition-colors px-4 rounded-r-lg shrink-0"
							aria-label="<?php esc_attr_e( 'Subscribe', 'estore-child' ); ?>">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
							</svg>
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<div class="mt-12 pt-6 border-t border-white/[0.06] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-faint">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> &middot; <?php bloginfo( 'name' ); ?> &middot; <?php esc_html_e( 'All Rights Reserved', 'estore-child' ); ?></p>

			<div class="flex items-center gap-2.5">
				<?php foreach ( $socials as $key => list( $label, $path ) ) : ?>
					<?php $url = get_theme_mod( $key ); ?>
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"
							class="w-8 h-8 grid place-items-center rounded-lg border border-white/10 text-muted hover:text-white hover:border-white/25 transition-colors"
							aria-label="<?php echo esc_attr( $label ); ?>">
							<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="<?php echo esc_attr( $path ); ?>" /></svg>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
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
