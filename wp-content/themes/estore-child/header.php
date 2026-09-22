<?php
/**
 * Site header.
 *
 * Third-party front-end libs are CDN tags here rather than wp_enqueue_*, to
 * match the leminar-saudi convention. style.css is linked with a ?v= cache
 * buster so local edits never serve stale.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
	<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

	<!-- Tailwind CDN play script: layout utilities only. Brand tokens live in
	     assets/scss/style.scss - there is no tailwind.config in this project. -->
	<script src="https://cdn.tailwindcss.com"></script>

	<link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/scss/style.css' ); ?>?v=<?php echo time(); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-ink text-white' ); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
	<div class="shell w-full flex items-center justify-between gap-8">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center shrink-0">
			<?php $logo = get_theme_mod( 'header_logo' ); ?>
			<?php if ( $logo ) : ?>
				<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto">
			<?php else : ?>
				<span class="text-lg font-semibold tracking-tight"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<nav class="hidden lg:block" aria-label="<?php esc_attr_e( 'Main', 'estore-child' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'main-menu',
				'container'      => false,
				'menu_class'     => 'flex items-center gap-8',
				'walker'         => new Estore_Nav_Walker(),
				'fallback_cb'    => false,
			) );
			?>
		</nav>

		<div class="flex items-center gap-3">
			<?php
			// "Sign in" in the design. Points at the WP login unless a dedicated
			// account page exists, so it never becomes a dead link.
			$account = get_page_by_path( 'account' );
			$sign_in = $account ? get_permalink( $account ) : wp_login_url( home_url( '/' ) );
			?>
			<a href="<?php echo esc_url( $sign_in ); ?>" class="btn btn--primary hidden sm:inline-flex !py-2.5 !px-5 text-sm">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 1115 0" />
				</svg>
				<?php esc_html_e( 'Sign in', 'estore-child' ); ?>
			</a>

			<button type="button" class="lg:hidden w-10 h-10 grid place-items-center rounded-full border border-white/10"
				id="drawer-open" aria-label="<?php esc_attr_e( 'Open menu', 'estore-child' ); ?>"
				aria-controls="mobile-drawer" aria-expanded="false">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
				</svg>
			</button>
		</div>
	</div>
</header>

<!-- Mobile drawer -->
<div class="fixed inset-0 bg-black/60 opacity-0 invisible transition-opacity duration-300 z-[55]" id="drawer-backdrop"></div>
<aside class="drawer p-6" id="mobile-drawer" aria-hidden="true">
	<div class="flex items-center justify-between mb-8">
		<span class="text-sm text-muted"><?php esc_html_e( 'Menu', 'estore-child' ); ?></span>
		<button type="button" class="w-9 h-9 grid place-items-center rounded-full border border-white/10"
			id="drawer-close" aria-label="<?php esc_attr_e( 'Close menu', 'estore-child' ); ?>">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
				<path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
			</svg>
		</button>
	</div>
	<?php
	wp_nav_menu( array(
		'theme_location' => 'main-menu',
		'container'      => false,
		'menu_class'     => 'flex flex-col gap-5 text-lg',
		'walker'         => new Estore_Nav_Walker(),
		'fallback_cb'    => false,
	) );
	?>
</aside>

<main id="content">
