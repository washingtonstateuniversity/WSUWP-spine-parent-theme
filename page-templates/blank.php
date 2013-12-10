<?php /* Template Name: Blank */ ?>

<?php get_header(); ?>

<main id="page" role="main" class="skeleton">

<?php while ( have_posts() ) : the_post(); ?>

<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content(); ?>
</div><!-- #post -->

<?php endwhile; ?>

</main><!--/#page-->

<?php get_template_part( 'spine/body' ); ?>

<?php get_footer(); ?>