<?php get_header(); ?>

<main class="archive">

<header class="main-header category-header">
    <div class="parent-header"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
    <div class="child-header">
	    <?php
		if ( is_day() ) : echo get_the_date();
		elseif ( is_month() ) : echo get_the_date( 'F Y' );
		elseif ( is_year() )  : echo get_the_date( 'Y' );
		else : echo 'Archives';
		endif;
		?>
    </div>
</header>

<?php if ( have_posts() ) : ?>
<header class="articles-header">
	
</header><!-- .archive-header -->

<section class="row sidebar">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-header">
				<h2 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			</header>
			<?php the_content(); ?>
		</article>

		<?php endwhile; ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

<?php endif; ?>

</main><!--/#page-->

<?php get_footer(); ?>