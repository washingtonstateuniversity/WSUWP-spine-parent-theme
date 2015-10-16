<?php

get_header();

// If a featured image is assigned to the post, display as a background image.
if ( spine_has_background_image() ) {
	$background_image_src = spine_get_background_image_src();

	?><style> html { background-image: url('<?php echo esc_url( $background_image_src ); ?>'); }</style><?php
}
?>

<main>

<?php

get_template_part('parts/headers');

if ( function_exists( 'wsuwp_uc_get_object_type_slugs' ) && in_array( get_post_type(), wsuwp_uc_get_object_type_slugs() ) ) {
	if ( 'wsuwp_uc_person' === get_post_type() ) {
		get_template_part( 'parts/single-layout', 'wsuwp_uc_person' );
	} else {
		get_template_part( 'parts/single-layout', 'university-center' );
	}
} else {
	if ( spine_has_featured_image() ) {
		$featured_image_src = spine_get_featured_image_src();
		$featured_image_position = get_post_meta( get_the_ID(), '_featured_image_position', true );

		if ( ! $featured_image_position || $featured_image_position !== sanitize_html_class( $featured_image_position ) ) {
			$featured_image_position = '';
		}
		?><figure class="featured-image <?php echo $featured_image_position; ?>" style="background-image: url('<?php echo esc_url( $featured_image_src ); ?>');"><?php spine_the_featured_image(); ?></figure><?php
	}
	get_template_part( 'parts/single-layout', get_post_type() );
}
?>

<footer class="main-footer">
	<section class="row halves pager prevnext gutter pad-ends">
		<div class="column one">
			<?php previous_post_link(); ?>
		</div>
		<div class="column two">
			<?php next_post_link(); ?>
		</div>
	</section><!--pager-->
</footer>

	<?php get_template_part( 'parts/footers' ); ?>

</main><!--/#page-->

<?php get_footer(); ?>