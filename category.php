<?php get_header(); ?>

<main class="spine-category-template">

<?php get_template_part('parts/headers'); ?> 

<section class="row sidebar side-right gutter marginalize-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part( 'articles/post', get_post_format() ); ?>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

<?php

$args = array(
	'prev_text'    => __('« Previous'),
	'next_text'    => __('Next »'),
);

echo paginate_links( $args );

?>
</main>
<?php

get_footer();