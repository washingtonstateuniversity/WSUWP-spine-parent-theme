<?php get_header(); ?>

<main class="spine-tag-template">

<?php get_template_part('parts/headers'); ?> 

<section class="row sidebar side-right gutter marginalize-ends">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_format() ); ?>

		<?php endwhile; // end of the loop. ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main><!--/#page-->

<?php get_footer(); ?>