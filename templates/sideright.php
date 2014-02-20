<?php /* Template Name: Sideright */ ?>

<?php get_header(); ?>

<main>

<?php get_template_part('parts/headers'); ?> 

<section class="row sidebar">

	<div class="column one">
	
		<?php get_template_part('articles/article'); ?>
		
	</div><!--/column-->
	
	<div class="column two"></div>

</section>

</main>

<?php get_footer(); ?>