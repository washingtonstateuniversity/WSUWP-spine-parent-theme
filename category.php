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
/* @type WP_Query $wp_query */
global $wp_query;

$big = 99164;
$args = array(
	'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format'       => 'page/%#%',
	'total'        => $wp_query->max_num_pages, // Provide the number of pages this query expects to fill.
	'current'      => max( 1, get_query_var('paged') ), // Provide either 1 or the page number we're on.
);
echo paginate_links( $args );

?>
</main>
<?php

get_footer();