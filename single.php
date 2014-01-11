<?php get_header(); ?>

<main id="page" role="main" class="skeleton">


<header class="page-header">
    <h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <h1 class="section-title"><?php the_category(' '); ?></h1>
</header>


<section class="row margin">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-header">
				<h1 class="article-title"><?php the_title(); ?></h1>
			</header>
			<div class="article-body">
				<?php the_content(); ?>
			</div>
	
		</article>

		<?php endwhile; // end of the loop. ?>
		
	</div><!--/column-->

	<div class="column two">
		
	</div><!--/column two-->

</section>

<footer>
<section class="row halves pager">
	<div class="column one">
		<?php previous_post_link(); ?> 
	</div>
	<div class="column two">
		<?php next_post_link(); ?>
	</div>
</section><!--pager-->
</footer>

</main><!--/#page-->

<a href="<?php echo get_edit_post_link(); ?>" class="wp-edit-link">Edit</a>

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>