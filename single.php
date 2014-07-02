<?php

get_header();

// If a featured image is assigned to the post, display as a background image.
if ( spine_has_featured_image() ) {
	$image = spine_get_featured_image_src( 'single-post-thumbnail' );
	?><style> main { background-image: url(<?php echo esc_url( $image ); ?>); }</style><?php
}

// If a position has been assigned to the featured image as a background, apply that style.
$position = get_post_meta( get_the_id(), 'position', true );
if ( ! empty( $position ) ) {
	$position = absint( $position ) - 132;
	?><style>main section:nth-of-type(1) { margin-top: <?php echo $position; ?>px; }</style><?php
}

?>

<main>

<?php get_template_part('parts/headers'); ?>

<section class="row sidebar side-right gutter marginalize-ends">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part( 'articles/post' ) ?>

			<?php // get_comments( ); ?>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

<footer class="main-footer">
	<section class="row halves pager prevnext">
		<div class="column one">
			<?php previous_post_link(); ?> 
		</div>
		<div class="column two">
			<?php next_post_link(); ?>
		</div>
	</section><!--pager-->
</footer>

</main><!--/#page-->

<a href="<?php echo get_edit_post_link(); ?>" class="wp-edit-link">Edit</a>

<?php get_footer(); ?>