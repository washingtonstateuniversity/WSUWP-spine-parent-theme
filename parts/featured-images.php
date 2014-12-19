<?php

// If a featured image is assigned to the post, display as a background image.

if ( spine_has_background_image() ) {
	$background_image_src = spine_get_background_image_src();
	?>

	<style> #jacket { background-image: url(<?php echo esc_url( $background_image_src ); ?>); }</style>

<?php } ?>

<?php if ( spine_has_featured_image() ) {
$featured_image_src = spine_get_featured_image_src(); ?>
<figure class="featured-image" style="background-image: url('<?php echo $featured_image_src ?>');">
	<?php spine_the_featured_image(); ?>
</figure>
<?php } ?>