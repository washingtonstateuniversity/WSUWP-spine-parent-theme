<?php get_header(); ?>

<main class="category">

<header class="bookmark">
	<hgroup>
	    <div class="site"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
	    <div class="section"><?php section_title('parent'); ?></div>
	    <div class="category"><?php echo single_cat_title(); ?></div>
	</hgroup>
</header>

<section class="row sidebar">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>
				
			<?php get_template_part( 'articles/article', get_post_format() ); ?>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

<?php
global $wp_query;

$big = 99631; // need an unlikely integer
$args = array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format'       => 'page/%#%',
	// 'total'        => 5,
	// 'current'      => 0,
	'show_all'     => False,
	// 'end_size'     => 3,
	// 'mid_size'     => 4,
	'prev_next'    => True,
	'prev_text'    => __('« Previous'),
	'next_text'    => __('Next »'),
	// 'type'         => 'plain',
	'add_args'     => False,
	'add_fragment' => ''
); ?>

<?php echo paginate_links( $args ); ?>

</main><!--/#page-->

<?php get_footer(); ?>