<?php get_header(); ?>

<main role="main">

<header class="page-header">
    <h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <h1 class="site-tagline"><a href="index.html"><?php bloginfo( 'description' ); ?></a></h1>
</header><!--siteID-->

<section class="row sidebar">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<?php the_content(); ?>
		</article>

		<?php endwhile; // end of the loop. ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main><!--/#page-->

<span class="wp-edit-link"><?php get_edit_post_link(); ?>Edit</span>

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>