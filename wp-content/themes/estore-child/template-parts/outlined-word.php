<?php
/**
 * The outlined display word in a hero ("AUDIO", "PRODUCTS").
 *
 * Figma strokes these with strokeAlign: OUTSIDE at 2px. Neither
 * -webkit-text-stroke nor a plain SVG stroke can do that - both centre the
 * stroke, pushing half of it inside the glyph. In tight concave corners (the
 * crook of an R) the two halves meet and fill in, leaving a visible wedge.
 *
 * So: stroke at double width and mask away the glyph interior, which leaves
 * exactly the outer half - the same result Figma renders.
 *
 * $args['text'] - the word.
 */
$word = trim( (string) ( $args['text'] ?? '' ) );
if ( '' === $word ) {
	return;
}
$mask_id = 'ow-' . wp_unique_id();
?>
<svg class="outlined-svg" aria-hidden="true" focusable="false" preserveAspectRatio="xMinYMin meet">
	<defs>
		<mask id="<?php echo esc_attr( $mask_id ); ?>" maskUnits="userSpaceOnUse" x="-50" y="-200" width="4000" height="600">
			<rect x="-50" y="-200" width="4000" height="600" fill="#fff" />
			<text x="0" y="0.78em" fill="#000" stroke="none"><?php echo esc_html( $word ); ?></text>
		</mask>
	</defs>
	<text x="0" y="0.78em" mask="url(#<?php echo esc_attr( $mask_id ); ?>)"><?php echo esc_html( $word ); ?></text>
</svg>
<span class="sr-only"><?php echo esc_html( $word ); ?></span>
