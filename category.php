<?php get_header(); ?>

<main id="page" role="main" class="skeleton">

<header class="topmost-header listings-header category-header">
    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <h1><a href="index.html"><?php bloginfo( 'description' ); ?></a></h1>
</header>

<section class="row sidebar">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-header">
				<h2 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			</header>
			<?php the_content(''); ?>
			<footer>
				<a href="<?php the_permalink(); ?>" rel="bookmark">Read More...</a>
			</footer>
		</article>

		<?php endwhile; // end of the loop. ?>
		
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

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>