<?php
/**
 * Template Name: Contact Page Template
 *
 * Renders the Contact Form 7 form whose ID is in the CFS field `contact_form_id`,
 * beside the Customizer contact details.
 */
get_header();

$form_id = function_exists( 'cfs' ) ? cfs()->get( 'contact_form_id' ) : '';
$email   = get_theme_mod( 'email_link' );
$phone   = get_theme_mod( 'phone_link' );
$address = get_theme_mod( 'address_link' );
?>

<?php get_template_part( 'template-parts/hero-banner' ); ?>

<section class="py-20 lg:py-[100px]">
	<div class="shell grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

		<div data-aos="fade-up">
			<?php if ( $form_id && shortcode_exists( 'contact-form-7' ) ) : ?>
				<?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ); ?>
			<?php else : ?>
				<p class="text-muted text-sm">
					<?php esc_html_e( 'Set the Contact Form 7 form ID in the page’s "Contact Form ID" field.', 'estore-child' ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="card p-8 h-fit" data-aos="fade-up" data-aos-delay="80">
			<h2 class="text-lg font-semibold mb-6"><?php esc_html_e( 'Reach us', 'estore-child' ); ?></h2>
			<ul class="flex flex-col gap-5 text-sm text-muted">
				<?php if ( $email ) : ?>
					<li><span class="block text-faint text-xs uppercase tracking-widest mb-1"><?php esc_html_e( 'Email', 'estore-child' ); ?></span>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-white hover:text-accent transition-colors"><?php echo esc_html( $email ); ?></a></li>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<li><span class="block text-faint text-xs uppercase tracking-widest mb-1"><?php esc_html_e( 'Phone', 'estore-child' ); ?></span>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="text-white hover:text-accent transition-colors"><?php echo esc_html( $phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<li><span class="block text-faint text-xs uppercase tracking-widest mb-1"><?php esc_html_e( 'Address', 'estore-child' ); ?></span>
						<span class="leading-relaxed"><?php echo nl2br( esc_html( $address ) ); ?></span></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</section>

<?php get_footer(); ?>
