<?php /* Template Name: Single */ ?>

<?php get_header(); ?>

<main>

<?php get_template_part('parts/headers'); ?> 

<section class="row single">

	<div class="column one">
	
		<?php get_template_part('articles/article'); ?>
		
	</div><!--/column-->

</section>

</main>

<?php get_footer(); ?>