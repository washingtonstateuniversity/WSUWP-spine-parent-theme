<?php

// If a background image is assigned to the post, attach it as a background to jacket.
if ( spine_has_background_image() ) {
	$background_image_src = spine_get_background_image_src();

	?><style> #jacket { background-image: url('<?php echo esc_url( $background_image_src ); ?>'); }</style><?php
}

// If a featured image is assigned to the post, output it as a figure with a background image accordingly.
if ( spine_has_featured_image() ) {
	$featured_image_src = spine_get_featured_image_src();
	$featured_image_position = get_post_meta( get_the_ID(), '_featured_image_position', true );

	if ( ! $featured_image_position || sanitize_html_class( $featured_image_position ) !== $featured_image_position ) {
		$featured_image_position = '';
	}

	?><figure class="featured-image <?php echo $featured_image_position; ?>" style="background-image: url('<?php echo esc_url( $featured_image_src ); ?>');"><?php spine_the_featured_image(); ?></figure><?php
}
