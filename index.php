<?php

get_header();

if ( is_home() ) {
	$main_class = 'spine-main-index';
} elseif ( is_author() ) {
	$main_class = 'spine-author-index';
} elseif ( is_category() ) {
	$main_class = 'spine-category-index';
} elseif ( is_tag() ) {
	$main_class = 'spine-tag-index';
} elseif ( is_tax() ) {
	$main_class = 'spine-tax-index';
} elseif ( is_archive() ) {
	$main_class = 'spine-archive-index';
}

?>

<main class="<?php echo $main_class; ?>">

<?php get_template_part('parts/headers'); ?> 

<section class="row side-right gutter marginalize-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part('articles/post'); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main><!--/#page-->

<?php get_footer(); ?>