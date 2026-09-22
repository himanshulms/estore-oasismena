<?php
/**
 * "Share via:" row on the single product page.
 * Plain share URLs - no third-party script, nothing to consent to.
 */
$url   = rawurlencode( get_permalink() );
$title = rawurlencode( get_the_title() );

$links = array(
	'Facebook'  => array( "https://www.facebook.com/sharer/sharer.php?u={$url}", 'M15 8h-3v8h-3V8H7V5.5h2V4.2C9 2.4 10 1.5 12 1.5h3V4h-2c-.6 0-1 .4-1 1v.5h3L15 8z' ),
	'X'         => array( "https://twitter.com/intent/tweet?url={$url}&text={$title}", 'M17.5 3h3l-6.6 7.5L21.8 21h-6l-4.7-6.1L5.7 21H2.6l7-8L2.2 3h6.2l4.2 5.6L17.5 3zm-1 16h1.7L7.6 4.8H5.8L16.5 19z' ),
	'LinkedIn'  => array( "https://www.linkedin.com/sharing/share-offsite/?url={$url}", 'M6.9 8.8v10.4H3.5V8.8h3.4zM5.2 3.4a2 2 0 110 4 2 2 0 010-4zM20.5 19.2h-3.4v-5.4c0-1.4-.5-2.3-1.7-2.3-.9 0-1.5.6-1.7 1.3-.1.2-.1.6-.1.9v5.5H10s.1-9.4 0-10.4h3.4v1.5c.5-.7 1.3-1.7 3.1-1.7 2.3 0 4 1.5 4 4.7v5.9z' ),
	'WhatsApp'  => array( "https://wa.me/?text={$title}%20{$url}", 'M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.5 6.5 0 01-3.2-2.8c-.1-.2 0-.4.1-.5l.4-.5.2-.4v-.4l-.7-1.7c-.2-.4-.4-.4-.6-.4h-.5a1 1 0 00-.7.3c-.3.3-.9.9-.9 2.1s.9 2.4 1 2.6c.2.2 1.8 2.8 4.4 3.9 1.7.7 2.3.8 3.1.6.5-.1 1.4-.6 1.6-1.2.2-.6.2-1 .1-1.1l-.3-.2z' ),
);
?>
<div class="flex items-center gap-4 mt-7">
	<span class="text-[15px] text-muted"><?php esc_html_e( 'Share via:', 'estore-child' ); ?></span>
	<div class="flex items-center gap-2.5">
		<?php foreach ( $links as $label => list( $href, $path ) ) : ?>
			<a href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener noreferrer"
				class="w-9 h-9 grid place-items-center rounded-full border border-white/15 text-muted hover:text-white hover:border-white/35 transition-colors"
				aria-label="<?php printf( esc_attr__( 'Share on %s', 'estore-child' ), esc_attr( $label ) ); ?>">
				<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="<?php echo esc_attr( $path ); ?>" /></svg>
			</a>
		<?php endforeach; ?>
	</div>
</div>
