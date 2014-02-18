<?php /* Template Name: Single */ ?>

<?php get_header(); ?>

<main>

<?php get_template_part('parts/headers'); ?> 

<section class="row single">

	<div class="column one">
	
		<?php while ( have_posts() ) : the_post(); ?>
				
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-header">
				<h1 class="article-title"><?php the_title(); ?></h1>
			</header>
			<?php the_content(); ?>
		</article>

		<?php endwhile; ?>
		
	</div><!--/column-->

</section>

</main>

<?php get_footer(); ?>