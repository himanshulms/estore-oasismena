<?php
/**
 * Media.
 *
 * Split out of functions.php so day-to-day work does not churn that file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Uploads: convert JPG/PNG to WebP on upload.
 * ---------------------------------------------------------------------- */
add_filter( 'wp_handle_upload', 'estore_convert_image_to_webp', 10, 2 );
// Sideloads (programmatic imports, media_sideload_image) go through a different
// filter, so register there too or imported files stay PNG/JPG.
add_filter( 'wp_handle_sideload', 'estore_convert_image_to_webp', 10, 2 );
function estore_convert_image_to_webp( $upload, $context ) {
	$allowed = array( 'image/jpeg', 'image/png' );
	if ( ! in_array( $upload['type'], $allowed, true ) || ! function_exists( 'imagewebp' ) ) {
		return $upload;
	}

	$file = $upload['file'];
	$img  = 'image/jpeg' === $upload['type'] ? @imagecreatefromjpeg( $file ) : @imagecreatefrompng( $file );
	if ( ! $img ) {
		return $upload;
	}

	if ( 'image/png' === $upload['type'] ) {
		imagepalettetotruecolor( $img );
		imagealphablending( $img, true );
		imagesavealpha( $img, true );
	}

	$webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );
	if ( imagewebp( $img, $webp, 85 ) ) {
		imagedestroy( $img );
		@unlink( $file );
		$upload['file'] = $webp;
		$upload['url']  = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $upload['url'] );
		$upload['type'] = 'image/webp';
	} else {
		imagedestroy( $img );
	}

	return $upload;
}

/**
 * Resolve any stored image reference to a URL valid on THIS site.
 *
 * Image fields (CFS `file`, the Customizer logos, the category term meta) save
 * an absolute URL. Move the database to another machine - a different domain,
 * a different folder - and all 149 of them still point at the old host, so
 * every image 404s even though the files are present.
 *
 * Accepts an attachment ID or a URL, and rewrites anything under
 * /wp-content/uploads/ onto this install's upload directory. Nothing needs
 * re-saving, and images keep working wherever the site is deployed.
 */
function estore_image_url( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}

	// Attachment ID - always resolved against the current site.
	if ( ctype_digit( $value ) ) {
		return (string) wp_get_attachment_url( (int) $value );
	}

	// An uploads URL, possibly from another host: keep the path, swap the base.
	if ( preg_match( '#/wp-content/uploads/(.+)$#', $value, $m ) ) {
		$dir = wp_get_upload_dir();
		return trailingslashit( $dir['baseurl'] ) . ltrim( $m[1], '/' );
	}

	return $value;
}
