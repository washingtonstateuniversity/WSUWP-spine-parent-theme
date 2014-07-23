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

			<?php get_template_part( 'articles/post', get_post_format() ); ?>

		<?php endwhile; // end of the loop. ?>

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
?>
	<footer class="main-footer archive-footer">
		<section class="row side-right pager prevnext gutter">
			<div class="column one">
				<?php echo paginate_links( $args ); ?>
			</div>
			<div class="column two">
				<!-- intentionally empty -->
			</div>
		</section><!--pager-->
	</footer>
</main>
<?php

get_footer();