<?php get_header(); ?>

<main>

<header class="main-header category-header">
    <div class="parent-header site"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
    <div class="child-header section"><?php section_title('parent'); ?></div>
    <div class="child-header page"><?php the_title(); ?></div>
</header>


<section class="row sidebar">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-header">
				<h1 class="article-title"><?php the_title(); ?></h1>
			</header>
			
			<?php the_content(); ?>
		</article>

		<?php endwhile; // end of the loop. ?>
		
	</div><!--/column-->

	<div class="column two">
		
		<?php get_sidebar(); ?>
		
	</div><!--/column two-->

</section>

</main>

<span class="wp-edit-link"><?php get_edit_post_link(); ?>Edit</span>

<?php get_footer(); ?>