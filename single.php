<?php

get_header();

// If a featured image is assigned to the post, display as a background image.
if ( spine_has_background_image() ) {
	$background_image_src = spine_get_background_image_src();
	?><style> html { background-image: url(<?php echo esc_url( $background_image_src ); ?>); }</style><?php
	}

// If a position has been assigned to the featured image as a background, apply that style.
/* $position = get_post_meta( get_the_id(), 'position', true );
if ( ! empty( $position ) ) {
	$position = absint( $position ) - 132;
	?><style>main section:nth-of-type(1) { margin-top: <?php echo $position; ?>px; }</style><?php
} */

?>

<main>

<?php get_template_part('parts/headers'); ?>

<?php if ( spine_has_featured_image() ) : ?> 
<?php $featured_image_src = spine_get_featured_image_src(); ?>
<figure class="featured-image" style="background-image: url('<?php echo $featured_image_src ?>');">
	<?php spine_the_featured_image(); ?>
</figure>
<?php endif; ?>

<section class="row side-right gutter pad-ends">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part( 'articles/post', get_post_type() ) ?>

			<?php // get_comments( ); ?>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

<footer class="main-footer">
	<section class="row halves pager prevnext gutter">
		<div class="column one">
			<?php previous_post_link(); ?> 
		</div>
		<div class="column two">
			<?php next_post_link(); ?>
		</div>
	</section><!--pager-->
</footer>

</main><!--/#page-->

<?php get_footer(); ?>