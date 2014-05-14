<?php
/**
 * Template Name: Builder Template
 *
 * @package ttf-one
 */

get_header();
?>

	<main class="spine-blank-template">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php remove_filter( 'the_content', 'wpautop', 10 ); ?>
				<?php the_content(); ?>
				<?php add_filter( 'the_content', 'wpautop', 10 ); ?>
			</div><!-- #post -->

		<?php endwhile; endif; ?>

	</main>

<?php get_footer(); ?>