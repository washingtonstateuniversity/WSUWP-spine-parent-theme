<?php get_header(); ?>

<main id="page" role="main" class="skeleton">

<header id="siteID">
    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <h1><a href="index.html"><?php bloginfo( 'description' ); ?></a></h1>
</header><!--siteID-->

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

</main><!--/#page-->

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>