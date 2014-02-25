<?php get_header(); ?>

<main class="spine-default-page">

<?php get_template_part('parts/headers'); ?> 

<section class="row sidebar">

	<div class="column one">
	
		<?php get_template_part('articles/article'); ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main>

<?php get_footer(); ?>