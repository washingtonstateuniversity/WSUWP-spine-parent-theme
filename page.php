<?php

get_header();

// If a featured image is assigned to the post, display as a background image.
if ( spine_has_background_image() ) {
	$background_image_src = spine_get_background_image_src();
	?><style> html { background-image: url(<?php echo esc_url( $background_image_src ); ?>); }</style><?php
}

?>

<main class="spine-page-default">

<?php get_template_part('parts/headers'); ?>

<?php if ( spine_has_featured_image() ) { 
$featured_image_src = spine_get_featured_image_src(); ?>
<figure class="featured-image" style="background-image: url('<?php echo $featured_image_src ?>');">
	<?php spine_the_featured_image(); ?>
</figure>
<?php } ?>

<section class="row side-right gutter marginalize-ends">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
	
			<?php get_template_part('articles/article'); ?>
		
		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main>

<?php get_footer(); ?>